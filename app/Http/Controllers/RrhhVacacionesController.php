<?php

namespace App\Http\Controllers;

use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesBitacora;
use App\Models\RrhhVacacionesMovimiento;
use App\Services\Rrhh\EstadoCuentaVacaciones;
use App\Services\Rrhh\VacacionesService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RrhhVacacionesController extends Controller
{
    private const MENSAJES = [
        'required' => 'El campo :attribute es obligatorio.',
        'required_if' => 'El campo :attribute es obligatorio para este tipo de movimiento.',
        'required_with' => 'Indique la hora de inicio y la hora de fin.',
        'date' => 'Ingrese una fecha válida en :attribute.',
        'date_format' => 'Ingrese una hora válida en :attribute.',
        'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a la fecha de contratación.',
        'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
        'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        'numeric' => 'El campo :attribute debe ser un número.',
        'min' => 'El campo :attribute debe ser mayor que cero.',
        'max' => 'El campo :attribute es demasiado grande.',
        'in' => 'Seleccione una opción válida en :attribute.',
        'archivo.mimes' => 'El comprobante debe ser PDF, JPG o PNG.',
        'archivo.max' => 'El comprobante no debe superar 2 MB.',
    ];

    private const ATRIBUTOS = [
        'tipo_movimiento' => 'tipo de solicitud',
        'fecha_inicio' => 'fecha de inicio',
        'fecha_fin' => 'fecha de fin',
        'hora_inicio' => 'hora de inicio',
        'hora_fin' => 'hora de fin',
        'dias_manual' => 'días',
        'horas_manual' => 'horas',
        'operacion' => 'operación',
        'motivo' => 'motivo',
        'motivo_anulacion' => 'motivo de anulación',
    ];

    public function __construct(private VacacionesService $vacaciones) {}

    public function index(Request $request)
    {
        $anio = (int) $request->input('anio', date('Y'));
        $anio = $anio >= 2000 && $anio <= 2100 ? $anio : (int) date('Y');
        $hoy = CarbonImmutable::today();
        $critico = config('rrhh.vacaciones.saldo_critico');
        $alerta = config('rrhh.vacaciones.alerta_vencimiento_dias');
        $venceAcumuladas = $this->vacaciones->primerCierre();

        $query = RrhhEmpleado::where('estado', 'activo')
            ->with(['periodosVacaciones', 'movimientosVacaciones']);

        // 🔍 BUSQUEDA
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->search . '%')
                    ->orWhere('identidad', 'like', '%' . $request->search . '%');
            });
        }

        // 📌 AREA
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $filas = $query->orderBy('nombre_completo')->get()->map(function ($empleado) use ($anio, $hoy, $critico, $alerta) {
            $estado = $this->vacaciones->estadoCuenta($empleado, null, $hoy);
            $meses = $this->resumenMensual($empleado, $anio);
            $proximo = $estado->proximoVencimiento;
            $diasParaVencer = $proximo ? (int) $hoy->diffInDays($proximo['fecha']) : null;

            return [
                'empleado' => $empleado,
                'estado' => $estado,
                'meses' => $meses,
                'tomado_anio' => array_sum(array_column($meses, 'dias')),
                'medidor' => $estado->medidor($hoy),
                'dias_para_vencer' => $diasParaVencer,
                'vence_pronto' => $proximo && $diasParaVencer <= $alerta,
                'debe_salir' => $estado->saldo > $critico,
                'adelanto' => $estado->saldo < 0,
            ];
        });

        // Lo que requiere atención, sobre todo el grupo (antes de filtrar por saldo)
        $resumen = [
            'empleados' => $filas->count(),
            'disponibles' => $filas->sum(fn ($fila) => max(0, $fila['estado']->saldo)),
            'debe_salir' => $filas->where('debe_salir', true)->count(),
            'vence_pronto' => $filas->where('vence_pronto', true)->count(),
            'dias_por_vencer' => $filas->where('vence_pronto', true)->sum(fn ($fila) => $fila['estado']->proximoVencimiento['dias']),
            'adelanto' => $filas->where('adelanto', true)->count(),
        ];

        // 🎯 FILTRO POR SALDO
        if ($request->filled('pendiente')) {
            $filas = $filas->filter(fn ($fila) => match ($request->pendiente) {
                'con' => $fila['estado']->saldo > 0,
                'sin' => $fila['estado']->saldo <= 0,
                'critico' => $fila['debe_salir'],
                'por_vencer' => $fila['vence_pronto'],
                'adelanto' => $fila['adelanto'],
                default => true,
            });
        }

        $filas = match ($request->input('orden')) {
            'saldo' => $filas->sortByDesc(fn ($fila) => $fila['estado']->saldo),
            'vence' => $filas->sortBy(fn ($fila) => ($fila['estado']->proximoVencimiento['fecha'] ?? null)?->getTimestamp() ?? PHP_INT_MAX),
            default => $filas,
        };

        $pendientesAcreditar = RrhhEmpleado::where('estado', 'activo')
            ->with('periodosVacaciones')
            ->get()
            ->filter(fn ($empleado) => $this->vacaciones->requiereSincronizacion($empleado, $hoy))
            ->count();

        return view('rrhh.vacaciones.index', [
            'filas' => $filas->values(),
            'anio' => $anio,
            'hoy' => $hoy,
            'resumen' => $resumen,
            'areas' => collect(config('rrhh.areas'))
                ->merge(RrhhEmpleado::whereNotNull('area')->distinct()->pluck('area'))
                ->unique()->sort()->values(),
            'pendientesAcreditar' => $pendientesAcreditar,
            'venceAcumuladas' => $venceAcumuladas,
        ]);
    }

    /**
     * Días descontados y permisos de cada mes del año para la planilla.
     */
    private function resumenMensual(RrhhEmpleado $empleado, int $anio): array
    {
        $meses = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $meses[$mes] = ['dias' => 0.0, 'registros' => 0, 'permisos' => 0, 'permisos_horas' => 0.0];
        }

        foreach ($empleado->movimientosVacaciones as $movimiento) {
            if ($movimiento->estado !== 'aprobado' || $movimiento->fecha_inicio->year !== $anio) {
                continue;
            }

            $mes = $movimiento->fecha_inicio->month;

            if ($movimiento->tipo_movimiento === 'permiso_especial') {
                $meses[$mes]['permisos']++;
                $meses[$mes]['permisos_horas'] += (float) $movimiento->horas_equivalentes;
            } elseif (in_array($movimiento->tipo_movimiento, ['vacacion_dias', 'vacacion_horas'], true)) {
                $meses[$mes]['dias'] += $this->vacaciones->diasADescontar($movimiento);
                $meses[$mes]['registros']++;
            }
        }

        return $meses;
    }

    public function crearSolicitud(RrhhEmpleado $empleado)
    {
        if ($empleado->estado !== 'activo') {
            return redirect()
                ->route('vacaciones.historial', $empleado)
                ->with('error', 'El empleado está inactivo; no se pueden registrar vacaciones.');
        }

        $empleado->load(['periodosVacaciones', 'movimientosVacaciones']);

        return view('rrhh.vacaciones.crear_solicitud', [
            'empleado' => $empleado,
            'movimiento' => new RrhhVacacionesMovimiento(['tipo_movimiento' => 'vacacion_dias']),
            'estado' => $this->vacaciones->estadoCuenta($empleado),
            'jornada' => config('rrhh.jornada'),
        ]);
    }

    public function guardarSolicitud(Request $request)
    {
        $request->validate(['empleado_id' => ['required', 'exists:rrhh_empleados,id']]);

        $empleado = RrhhEmpleado::findOrFail($request->empleado_id);

        if ($empleado->estado !== 'activo') {
            return redirect()
                ->route('vacaciones.historial', $empleado)
                ->with('error', 'El empleado está inactivo; no se pueden registrar vacaciones.');
        }

        $movimiento = new RrhhVacacionesMovimiento([
            'empleado_id' => $empleado->id,
            'estado' => 'aprobado',
            'aprobado_por' => $this->usuario(),
            'fecha_aprobacion' => now(),
        ]);

        $this->aplicarFormulario($request, $empleado, $movimiento);

        DB::transaction(function () use ($request, $empleado, $movimiento) {
            if ($request->hasFile('archivo')) {
                $movimiento->archivo_comprobante = $request->file('archivo')->store('rrhh/vacaciones', 'local');
            }

            $movimiento->save();

            $estado = $this->sincronizar($empleado);

            RrhhVacacionesBitacora::registrar(
                $empleado->id,
                'REGISTRO_MOVIMIENTO',
                'Registrado: ' . $movimiento->descripcion()
                    . ($movimiento->es_adelanto ? ' — registrado como adelanto (saldo negativo)' : '')
                    . '. Saldo después del registro: ' . VacacionesService::formatoDias($estado->saldo) . '.',
                $movimiento->id
            );
        });

        return redirect()
            ->route('vacaciones.historial', $empleado)
            ->with('success', 'Solicitud registrada correctamente');
    }

    public function historial(RrhhEmpleado $empleado)
    {
        $empleado->load(['periodosVacaciones', 'movimientosVacaciones']);

        return view('rrhh.vacaciones.historial', [
            'empleado' => $empleado,
            'estado' => $this->vacaciones->estadoCuenta($empleado),
            'movimientos' => $empleado->movimientosVacaciones->sortByDesc(fn ($m) => [$m->fecha_inicio->toDateString(), $m->id])->values(),
            'bitacora' => $empleado->bitacoraVacaciones()->orderByDesc('fecha_evento')->orderByDesc('id')->get(),
            'requiereSincronizacion' => $this->vacaciones->requiereSincronizacion($empleado),
        ]);
    }

    public function editarMovimiento(RrhhVacacionesMovimiento $movimiento)
    {
        if ($movimiento->estado === 'anulado') {
            return redirect()
                ->route('vacaciones.historial', $movimiento->empleado_id)
                ->with('error', 'El movimiento está anulado y no se puede editar.');
        }

        $empleado = $movimiento->empleado->load(['periodosVacaciones', 'movimientosVacaciones']);

        return view('rrhh.vacaciones.editar_movimiento', [
            'empleado' => $empleado,
            'movimiento' => $movimiento,
            'estado' => $this->vacaciones->estadoCuenta($empleado),
            'jornada' => config('rrhh.jornada'),
        ]);
    }

    public function actualizarMovimiento(Request $request, RrhhVacacionesMovimiento $movimiento)
    {
        if ($movimiento->estado === 'anulado') {
            return redirect()
                ->route('vacaciones.historial', $movimiento->empleado_id)
                ->with('error', 'El movimiento está anulado y no se puede editar.');
        }

        $empleado = $movimiento->empleado;
        $antes = $movimiento->descripcion();
        $motivoAntes = $movimiento->motivo;

        $this->aplicarFormulario($request, $empleado, $movimiento);

        DB::transaction(function () use ($request, $empleado, $movimiento, $antes, $motivoAntes) {
            if ($request->hasFile('archivo')) {
                $movimiento->archivo_comprobante = $request->file('archivo')->store('rrhh/vacaciones', 'local');
            }

            $cambioArchivo = $movimiento->isDirty('archivo_comprobante');
            $movimiento->save();

            $cambios = [];

            if ($antes !== $movimiento->descripcion()) {
                $cambios[] = "{$antes} → {$movimiento->descripcion()}";
            }

            if ($motivoAntes !== $movimiento->motivo && $movimiento->tipo_movimiento !== 'ajuste_manual') {
                $cambios[] = 'motivo: «' . ($motivoAntes ?: '—') . '» → «' . ($movimiento->motivo ?: '—') . '»';
            }

            if ($cambioArchivo) {
                $cambios[] = 'se adjuntó un nuevo comprobante';
            }

            $estado = $this->sincronizar($empleado);

            RrhhVacacionesBitacora::registrar(
                $empleado->id,
                'EDICION_MOVIMIENTO',
                ($cambios ? 'Cambios: ' . implode('; ', $cambios) : 'Guardado sin cambios')
                    . '. Saldo actual: ' . VacacionesService::formatoDias($estado->saldo) . '.',
                $movimiento->id
            );
        });

        return redirect()
            ->route('vacaciones.historial', $empleado)
            ->with('success', 'Movimiento actualizado correctamente');
    }

    /**
     * Los movimientos no se borran: se anulan y quedan en el historial.
     */
    public function anularMovimiento(Request $request, RrhhVacacionesMovimiento $movimiento)
    {
        $datos = $request->validate([
            'motivo_anulacion' => ['required', 'string', 'max:500'],
        ], self::MENSAJES, self::ATRIBUTOS);

        if ($movimiento->estado === 'anulado') {
            return redirect()->route('vacaciones.historial', $movimiento->empleado_id);
        }

        $empleado = $movimiento->empleado;

        DB::transaction(function () use ($empleado, $movimiento, $datos) {
            $movimiento->estado = 'anulado';
            $movimiento->observacion = trim(($movimiento->observacion ? $movimiento->observacion . "\n" : '')
                . 'Anulado: ' . $datos['motivo_anulacion']);
            $movimiento->save();

            $estado = $this->sincronizar($empleado);

            RrhhVacacionesBitacora::registrar(
                $empleado->id,
                'ANULACION_MOVIMIENTO',
                'Anulado: ' . $movimiento->descripcion() . '. Motivo: ' . $datos['motivo_anulacion']
                    . '. Saldo actual: ' . VacacionesService::formatoDias($estado->saldo) . '.',
                $movimiento->id
            );
        });

        return redirect()
            ->route('vacaciones.historial', $empleado)
            ->with('success', 'Movimiento anulado; el saldo fue recalculado.');
    }

    /**
     * Descarga el comprobante (los archivos no son públicos).
     */
    public function comprobante(RrhhVacacionesMovimiento $movimiento)
    {
        $ruta = $movimiento->archivo_comprobante;

        abort_unless($ruta, 404);

        foreach (['local', 'public'] as $disco) {
            if (Storage::disk($disco)->exists($ruta)) {
                return Storage::disk($disco)->response($ruta);
            }
        }

        abort(404);
    }

    /**
     * Valida el formulario, calcula días/horas, revisa traslapes y saldo, y
     * llena el movimiento (sin guardarlo).
     */
    private function aplicarFormulario(Request $request, RrhhEmpleado $empleado, RrhhVacacionesMovimiento $movimiento): void
    {
        $datos = $request->validate([
            'tipo_movimiento' => ['required', Rule::in(array_keys(RrhhVacacionesMovimiento::TIPOS))],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:' . $empleado->fecha_contratacion->toDateString()],
            'fecha_fin' => ['nullable', 'required_if:tipo_movimiento,vacacion_dias', 'date', 'after_or_equal:fecha_inicio'],
            'hora_inicio' => ['nullable', 'required_if:tipo_movimiento,vacacion_horas', 'required_with:hora_fin', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'required_if:tipo_movimiento,vacacion_horas', 'required_with:hora_inicio', 'date_format:H:i', 'after:hora_inicio'],
            'dias_manual' => ['nullable', 'required_if:tipo_movimiento,ajuste_manual', 'numeric', 'min:0.01', 'max:365'],
            'horas_manual' => ['nullable', 'numeric', 'min:0.01', 'max:24'],
            'operacion' => ['nullable', 'required_if:tipo_movimiento,ajuste_manual', 'in:sumar,restar'],
            'motivo' => ['nullable', 'required_if:tipo_movimiento,ajuste_manual', 'string', 'max:1000'],
            'archivo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], self::MENSAJES, self::ATRIBUTOS);

        $tipo = $datos['tipo_movimiento'];
        $porHoras = $tipo === 'vacacion_horas'
            || ($tipo === 'permiso_especial' && ! empty($datos['hora_inicio']) && ! empty($datos['hora_fin']));

        // Normaliza los campos que no aplican al tipo elegido
        if ($tipo === 'ajuste_manual') {
            $datos['fecha_fin'] = null;
        } elseif ($porHoras) {
            $datos['fecha_fin'] = $datos['fecha_inicio'];
        } else {
            $datos['fecha_fin'] = $datos['fecha_fin'] ?? $datos['fecha_inicio'];
        }

        if (! $porHoras) {
            $datos['hora_inicio'] = $datos['hora_fin'] = null;
        }

        $cantidades = $this->vacaciones->calcularCantidades($tipo, $datos);

        if ($tipo !== 'ajuste_manual' && $cantidades['horas'] <= 0) {
            throw ValidationException::withMessages([
                $porHoras ? 'hora_fin' : 'fecha_fin' => $porHoras
                    ? 'Las horas seleccionadas quedan fuera de la jornada (8:00–12:00 y 13:00–17:00).'
                    : 'El rango seleccionado no tiene días a descontar (los domingos no se cuentan).',
            ]);
        }

        $movimiento->fill([
            'tipo_movimiento' => $tipo,
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $datos['hora_fin'],
            'dias_equivalentes' => $cantidades['dias'],
            'horas_equivalentes' => $cantidades['horas'],
            'motivo' => $datos['motivo'] ?? null,
            'mes_referencia' => CarbonImmutable::parse($datos['fecha_inicio'])->format('m'),
        ]);

        if ($traslape = $this->vacaciones->buscarTraslape($empleado, $movimiento)) {
            throw ValidationException::withMessages([
                'fecha_inicio' => 'Ya existe un movimiento registrado en esas fechas: ' . $traslape->descripcion() . '.',
            ]);
        }

        // Se permite adelantar: si no hay saldo queda negativo y se marca como
        // adelanto; se compensa con la siguiente acreditación por aniversario.
        $empleado->load(['periodosVacaciones', 'movimientosVacaciones']);
        $adelanto = $this->vacaciones->evaluarAdelanto($empleado, $movimiento);

        $movimiento->es_adelanto = $adelanto['requiere'] > 0 || $adelanto['propio'] > 0;
    }

    /**
     * Enlaza el movimiento con su periodo, recalcula y devuelve el estado de cuenta.
     */
    private function sincronizar(RrhhEmpleado $empleado): EstadoCuentaVacaciones
    {
        $this->vacaciones->sincronizarEmpleado($empleado, null, $this->usuario());

        return $this->vacaciones->estadoCuenta($empleado->load(['periodosVacaciones', 'movimientosVacaciones']));
    }

    private function usuario(): string
    {
        return Auth::user()?->name ?? 'Sistema';
    }
}
