<?php

namespace App\Http\Controllers;

use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesBitacora;
use App\Models\RrhhVacacionesMovimiento;
use App\Services\Rrhh\JornadaLaboral;
use App\Services\Rrhh\VacacionesService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Días colectivos: el mismo descuento de vacaciones para varios empleados a la
 * vez (Semana Santa, Semana Morazánica, 24 y 31 de diciembre). Cada empleado
 * recibe su propio movimiento, que se puede editar o anular por separado.
 */
class RrhhVacacionesColectivasController extends Controller
{
    private const MENSAJES = [
        'motivo.required' => 'Indique el motivo, por ejemplo «Semana Santa 2027».',
        'fecha_inicio.required' => 'Indique la fecha de inicio.',
        'fecha_fin.required' => 'Indique la fecha de fin.',
        'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la de inicio.',
        'date' => 'Ingrese una fecha válida.',
        'empleados.required' => 'Seleccione al menos un empleado.',
        'dias_manual.numeric' => 'Los días deben ser un número.',
        'dias_manual.min' => 'Los días deben ser mayores que cero.',
        'dias_manual.max' => 'Los días son demasiados para un registro colectivo.',
    ];

    public function __construct(
        private VacacionesService $vacaciones,
        private JornadaLaboral $jornada,
    ) {}

    public function crear()
    {
        $hoy = CarbonImmutable::today();

        $empleados = RrhhEmpleado::where('estado', 'activo')
            ->with(['periodosVacaciones', 'movimientosVacaciones'])
            ->orderByRaw('area is null')
            ->orderBy('area')
            ->orderBy('nombre_completo')
            ->get();

        return view('rrhh.vacaciones.colectivos', [
            'grupos' => $empleados->groupBy(fn ($empleado) => $empleado->area ?: 'Sin área asignada'),
            'saldos' => $empleados->mapWithKeys(fn ($empleado) => [
                $empleado->id => $this->vacaciones->estadoCuenta($empleado, null, $hoy)->saldo,
            ]),
            'sugerencias' => $this->sugerencias($hoy),
            'jornada' => config('rrhh.jornada'),
        ]);
    }

    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'motivo' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'dias_manual' => ['nullable', 'numeric', 'min:0.25', 'max:31'],
            'empleados' => ['required', 'array', 'min:1'],
            'empleados.*' => ['integer'],
        ], self::MENSAJES);

        $cantidades = $this->vacaciones->calcularCantidades('vacacion_dias', $datos);

        if ($cantidades['dias'] <= 0) {
            throw ValidationException::withMessages([
                'fecha_fin' => 'El rango seleccionado no tiene días a descontar (los domingos no se cuentan).',
            ]);
        }

        $usuario = Auth::user()?->name ?? 'Sistema';
        $inicio = CarbonImmutable::parse($datos['fecha_inicio'])->startOfDay();
        $registrados = 0;
        $omitidos = [];

        $empleados = RrhhEmpleado::whereIn('id', $datos['empleados'])
            ->where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        DB::transaction(function () use ($empleados, $datos, $cantidades, $usuario, $inicio, &$registrados, &$omitidos) {
            foreach ($empleados as $empleado) {
                if ($empleado->fecha_contratacion->gt($inicio)) {
                    $omitidos[] = "{$empleado->nombre_completo}: ingresó después de esas fechas";

                    continue;
                }

                $movimiento = new RrhhVacacionesMovimiento([
                    'empleado_id' => $empleado->id,
                    'tipo_movimiento' => 'vacacion_dias',
                    'estado' => 'aprobado',
                    'fecha_inicio' => $datos['fecha_inicio'],
                    'fecha_fin' => $datos['fecha_fin'],
                    'dias_equivalentes' => $cantidades['dias'],
                    'horas_equivalentes' => $cantidades['horas'],
                    'motivo' => $datos['motivo'],
                    'observacion' => 'Registro colectivo',
                    'mes_referencia' => $inicio->format('m'),
                    'aprobado_por' => $usuario,
                    'fecha_aprobacion' => now(),
                ]);

                if ($traslape = $this->vacaciones->buscarTraslape($empleado, $movimiento)) {
                    $omitidos[] = "{$empleado->nombre_completo}: ya tiene registrado {$traslape->descripcion()}";

                    continue;
                }

                $adelanto = $this->vacaciones->evaluarAdelanto(
                    $empleado->load(['periodosVacaciones', 'movimientosVacaciones']),
                    $movimiento
                );
                $movimiento->es_adelanto = $adelanto['requiere'] > 0 || $adelanto['propio'] > 0;
                $movimiento->save();

                $this->vacaciones->sincronizarEmpleado($empleado, null, $usuario);
                $saldo = $this->vacaciones->estadoCuenta($empleado->load(['periodosVacaciones', 'movimientosVacaciones']))->saldo;

                RrhhVacacionesBitacora::registrar(
                    $empleado->id,
                    'REGISTRO_COLECTIVO',
                    "Registro colectivo «{$datos['motivo']}»: {$movimiento->descripcion()}"
                        . ($movimiento->es_adelanto ? ' — queda como adelanto' : '')
                        . '. Saldo después del registro: ' . VacacionesService::formatoDias($saldo) . '.',
                    $movimiento->id,
                    $usuario
                );

                $registrados++;
            }
        });

        if ($registrados === 0) {
            return back()
                ->withInput()
                ->with('error', 'No se registró a ningún empleado.')
                ->with('omitidos', $omitidos);
        }

        return redirect()
            ->route('vacaciones.index', ['anio' => $inicio->year])
            ->with('success', "Se registró «{$datos['motivo']}» ("
                . VacacionesService::formatoDias($cantidades['dias']) . ') a ' . $registrados
                . ($registrados === 1 ? ' empleado.' : ' empleados.'))
            ->with('omitidos', $omitidos);
    }

    /**
     * Fechas que se repiten cada año, para llenar el formulario con un clic.
     * La Semana Morazánica se define por decreto y se ingresa a mano.
     */
    private function sugerencias(CarbonImmutable $hoy): array
    {
        $sugerencias = [];

        foreach ([$hoy->year, $hoy->year + 1] as $anio) {
            $pascua = $this->jornada->domingoDeResurreccion($anio);

            $sugerencias[] = ['motivo' => "Semana Santa {$anio}", 'inicio' => $pascua->subDays(4), 'fin' => $pascua->subDays(2)];
            $sugerencias[] = ['motivo' => "24 de diciembre de {$anio}", 'inicio' => CarbonImmutable::create($anio, 12, 24), 'fin' => CarbonImmutable::create($anio, 12, 24)];
            $sugerencias[] = ['motivo' => "31 de diciembre de {$anio}", 'inicio' => CarbonImmutable::create($anio, 12, 31), 'fin' => CarbonImmutable::create($anio, 12, 31)];
        }

        return collect($sugerencias)
            ->filter(fn ($sugerencia) => $sugerencia['fin']->gte($hoy))
            ->sortBy(fn ($sugerencia) => $sugerencia['inicio']->getTimestamp())
            ->take(4)
            ->values()
            ->all();
    }
}
