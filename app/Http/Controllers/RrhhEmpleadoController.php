<?php

namespace App\Http\Controllers;

use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesBitacora;
use App\Services\Rrhh\VacacionesService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RrhhEmpleadoController extends Controller
{
    private const MENSAJES = [
        'required' => 'El campo :attribute es obligatorio.',
        'identidad.regex' => 'La identidad debe tener el formato 0000-0000-00000.',
        'identidad.unique' => 'Ya existe un empleado registrado con esta identidad.',
        'telefono.regex' => 'El teléfono debe tener el formato +504 0000-0000.',
        'email' => 'Ingrese un correo electrónico válido.',
        'in' => 'Seleccione una opción válida en :attribute.',
        'date' => 'Ingrese una fecha válida en :attribute.',
        'after' => 'La :attribute no es válida.',
        'numeric' => 'El campo :attribute debe ser un número.',
        'min' => 'El campo :attribute no puede ser negativo.',
        'max' => 'El campo :attribute es demasiado grande.',
    ];

    private const ATRIBUTOS = [
        'nombre_completo' => 'nombre completo',
        'correo' => 'correo electrónico',
        'telefono' => 'teléfono',
        'area' => 'área',
        'fecha_contratacion' => 'fecha de contratación',
        'vacaciones_acumuladas' => 'vacaciones acumuladas',
    ];

    public function __construct(private VacacionesService $vacaciones) {}

    public function index(Request $request)
    {
        $query = RrhhEmpleado::query()->with(['periodosVacaciones', 'movimientosVacaciones']);

        // 🔍 BUSQUEDA (nombre o identidad)
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

        // Conteo por estado con los mismos filtros de búsqueda y área
        $conteo = (clone $query)->toBase()->selectRaw('estado, count(*) as total')->groupBy('estado')->pluck('total', 'estado');
        $conteo = [
            'activo' => (int) ($conteo['activo'] ?? 0),
            'inactivo' => (int) ($conteo['inactivo'] ?? 0),
        ];
        $conteo['todos'] = $conteo['activo'] + $conteo['inactivo'];

        // 📌 ESTADO (por defecto solo activos)
        $estadoFiltro = in_array($request->input('estado'), ['activo', 'inactivo', 'todos'], true)
            ? $request->input('estado')
            : 'activo';

        if ($estadoFiltro !== 'todos') {
            $query->where('estado', $estadoFiltro);
        }

        // Ordenados por área para mostrarlos agrupados
        $empleados = $query->orderByRaw('area is null')->orderBy('area')->orderBy('nombre_completo')->get();

        // El saldo se calcula a partir de los movimientos, por eso el filtro
        // de pendientes se aplica antes de paginar.
        $hoy = CarbonImmutable::today();
        $estados = $empleados->mapWithKeys(fn ($empleado) => [
            $empleado->id => $this->vacaciones->estadoCuenta($empleado, null, $hoy),
        ]);

        if ($request->filled('pendiente')) {
            $critico = config('rrhh.vacaciones.saldo_critico');

            $empleados = $empleados->filter(fn ($empleado) => match ($request->pendiente) {
                'con' => $estados[$empleado->id]->saldo > 0,
                'sin' => $estados[$empleado->id]->saldo <= 0,
                'critico' => $estados[$empleado->id]->saldo > $critico,
                default => true,
            })->values();
        }

        $pagina = LengthAwarePaginator::resolveCurrentPage();
        $empleados = new LengthAwarePaginator(
            $empleados->forPage($pagina, 50)->values(),
            $empleados->count(),
            50,
            $pagina,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('rrhh.empleados.index', [
            'empleados' => $empleados,
            'estados' => $estados,
            'areas' => $this->areasRegistradas(),
            'conteo' => $conteo,
            'estadoFiltro' => $estadoFiltro,
            'hoy' => $hoy,
        ]);
    }

    public function create()
    {
        return view('rrhh.empleados.create', [
            'areas' => config('rrhh.areas'),
            'cargos' => config('rrhh.cargos'),
        ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        $empleado = RrhhEmpleado::create($datos + ['estado' => 'activo']);
        $venceAcumuladas = $this->vacaciones->vencimientoAcumuladoHistorico($empleado);

        RrhhVacacionesBitacora::registrar(
            $empleado->id,
            'ALTA_EMPLEADO',
            'Empleado registrado. Fecha de contratación: ' . $empleado->fecha_contratacion->format('d-m-Y')
                . '. Vacaciones acumuladas (histórico): ' . VacacionesService::numero($empleado->vacaciones_acumuladas) . ' días'
                . match (true) {
                    $empleado->vacaciones_acumuladas <= 0 => '.',
                    $venceAcumuladas === null => ', no vencen (área sin vencimiento).',
                    default => ', vencen el ' . $venceAcumuladas->format('d-m-Y') . '.',
                }
        );

        // Crea el periodo laboral vigente y acredita los días por ley
        $this->vacaciones->sincronizarEmpleado($empleado, null, $this->usuario());

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado registrado correctamente');
    }

    public function edit(RrhhEmpleado $empleado)
    {
        return view('rrhh.empleados.edit', [
            'empleado' => $empleado,
            'areas' => $this->opciones('areas', $empleado->area),
            'cargos' => $this->opciones('cargos', $empleado->cargo),
        ]);
    }

    public function update(Request $request, RrhhEmpleado $empleado)
    {
        $datos = $this->validar($request, $empleado);

        $empleado->fill($datos);

        $cambios = [];

        if ($empleado->isDirty('fecha_contratacion')) {
            $cambios[] = 'fecha de contratación ' . $empleado->getOriginal('fecha_contratacion')?->format('d-m-Y')
                . ' → ' . $empleado->fecha_contratacion->format('d-m-Y') . ' (se recalcularon los periodos)';
        }

        $areasSinVencimiento = config('rrhh.vacaciones.areas_sin_vencimiento', []);

        if ($empleado->isDirty('area')
            && in_array($empleado->getOriginal('area'), $areasSinVencimiento, true) !== in_array($empleado->area, $areasSinVencimiento, true)) {
            $cambios[] = 'área ' . ($empleado->getOriginal('area') ?: 'sin área') . ' → ' . ($empleado->area ?: 'sin área')
                . (in_array($empleado->area, $areasSinVencimiento, true)
                    ? ' (sus días ya no vencen)'
                    : ' (sus días vencen el 31 de diciembre de cada año)');
        }

        if ($empleado->isDirty('vacaciones_acumuladas')) {
            $cambios[] = 'vacaciones acumuladas ' . VacacionesService::numero($empleado->getOriginal('vacaciones_acumuladas'))
                . ' → ' . VacacionesService::numero($empleado->vacaciones_acumuladas) . ' días';
        }

        $empleado->save();

        if ($cambios) {
            RrhhVacacionesBitacora::registrar($empleado->id, 'EDICION_EMPLEADO', 'Cambios: ' . implode('; ', $cambios) . '.');
        }

        $this->vacaciones->sincronizarEmpleado($empleado, null, $this->usuario());

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado actualizado');
    }

    /**
     * Activa o desactiva al empleado. Al desactivarlo se registra la fecha de
     * salida y deja de recibir acreditaciones.
     */
    public function cambiarEstado(RrhhEmpleado $empleado)
    {
        $activar = $empleado->estado !== 'activo';

        $empleado->estado = $activar ? 'activo' : 'inactivo';
        $empleado->fecha_salida = $activar ? null : CarbonImmutable::today();
        $empleado->save();

        RrhhVacacionesBitacora::registrar(
            $empleado->id,
            'CAMBIO_ESTADO',
            $activar ? 'Empleado reactivado.' : 'Empleado desactivado. Fecha de salida: ' . $empleado->fecha_salida->format('d-m-Y') . '.'
        );

        $this->vacaciones->sincronizarEmpleado($empleado, null, $this->usuario());

        return redirect()
            ->back()
            ->with('success', 'Estado del empleado actualizado');
    }

    private function validar(Request $request, ?RrhhEmpleado $empleado = null): array
    {
        $this->normalizar($request);

        $datos = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'identidad' => [
                'nullable',
                'regex:/^\d{4}-\d{4}-\d{5}$/',
                Rule::unique('rrhh_empleados', 'identidad')->ignore($empleado?->id),
            ],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'regex:/^\+504 \d{4}-\d{4}$/'],
            'cargo' => ['nullable', Rule::in($this->opciones('cargos', $empleado?->cargo))],
            'area' => ['nullable', Rule::in($this->opciones('areas', $empleado?->area))],
            'fecha_contratacion' => ['required', 'date', 'after:1950-01-01'],
            'vacaciones_acumuladas' => ['nullable', 'numeric', 'min:0', 'max:999'],
        ], self::MENSAJES, self::ATRIBUTOS);

        $datos['vacaciones_acumuladas'] = $datos['vacaciones_acumuladas'] ?? 0;

        return $datos;
    }

    /**
     * Acepta identidad y teléfono con o sin guiones y los guarda con formato.
     */
    private function normalizar(Request $request): void
    {
        $identidad = preg_replace('/\D/', '', (string) $request->input('identidad'));
        $telefono = preg_replace('/\D/', '', (string) $request->input('telefono'));

        if (strlen($telefono) === 11 && str_starts_with($telefono, '504')) {
            $telefono = substr($telefono, 3);
        }

        $request->merge([
            'identidad' => match (true) {
                $identidad === '' => null,
                strlen($identidad) === 13 => substr($identidad, 0, 4) . '-' . substr($identidad, 4, 4) . '-' . substr($identidad, 8),
                default => $request->input('identidad'),
            },
            'telefono' => match (true) {
                $telefono === '' => null,
                strlen($telefono) === 8 => '+504 ' . substr($telefono, 0, 4) . '-' . substr($telefono, 4),
                default => $request->input('telefono'),
            },
        ]);
    }

    /**
     * Opciones del catálogo (config/rrhh.php) más el valor actual del empleado,
     * para no perder datos registrados antes de que existiera el catálogo.
     */
    private function opciones(string $catalogo, ?string $actual = null): array
    {
        $opciones = config("rrhh.{$catalogo}");

        if ($actual && ! in_array($actual, $opciones, true)) {
            $opciones[] = $actual;
        }

        return $opciones;
    }

    private function areasRegistradas(): array
    {
        return collect(config('rrhh.areas'))
            ->merge(RrhhEmpleado::whereNotNull('area')->distinct()->pluck('area'))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private function usuario(): string
    {
        return Auth::user()?->name ?? 'Sistema';
    }
}
