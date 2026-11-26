<?php

namespace App\Http\Controllers;

use App\Models\BitacoraActividad;
use App\Models\PlanActividad;
use App\Models\PlanTrabajo;
use App\Models\User;
use App\Services\Plan\AvanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BitacoraActividadController extends Controller
{
    private const FILTROS = [
        'buscar', 'estado', 'prioridad', 'tipo_falla', 'ubicacion', 'tecnico',
        'desde', 'hasta', 'plan', 'plan_actividad_id',
    ];

    public function __construct(private AvanceService $avances)
    {
    }

    /**
     * Mostrar todas las actividades (vista del gerente).
     */
    public function index(Request $request)
    {
        $filtros = $request->only(self::FILTROS);

        $actividades = BitacoraActividad::with(['user', 'planActividad.plan', 'ejecucionPlan'])
            ->filtrar($filtros)
            ->orderBy('fecha', 'DESC')
            ->orderBy('hora_inicio', 'DESC')
            ->paginate(15)
            ->withQueryString();

        // Contadores sobre todo lo filtrado (no solo la página visible); sin el
        // filtro de estado, para que las tarjetas sirvan para cambiar de estado
        $porEstado = BitacoraActividad::filtrar(Arr::except($filtros, 'estado'))
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $vinculadas = BitacoraActividad::filtrar($filtros)->whereNotNull('plan_actividad_id')->count();

        $totalMes = BitacoraActividad::whereBetween('fecha', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ])->count();

        $opciones = [
            'tipos_falla' => BitacoraActividad::whereNotNull('tipo_falla')->distinct()->orderBy('tipo_falla')->pluck('tipo_falla'),
            'ubicaciones' => BitacoraActividad::whereNotNull('ubicacion')->distinct()->orderBy('ubicacion')->pluck('ubicacion'),
            'tecnicos' => User::whereIn('id', BitacoraActividad::select('user_id')->distinct())->orderBy('name')->pluck('name', 'id'),
        ];

        $actividadPlanFiltrada = !empty($filtros['plan_actividad_id'])
            ? PlanActividad::with('plan')->find($filtros['plan_actividad_id'])
            : null;

        return view('bitacora.index', compact(
            'actividades', 'totalMes', 'porEstado', 'vinculadas', 'filtros', 'opciones', 'actividadPlanFiltrada'
        ));
    }

    /**
     * Formulario de creación rápida.
     */
    public function create(Request $request)
    {
        return view('bitacora.create', [
            'actividadesPlan' => $this->actividadesPlanDisponibles(),
            'planActividadSugerida' => $request->integer('plan_actividad_id') ?: null,
        ]);
    }

    /**
     * Guardar registro.
     */
    public function store(Request $request)
    {
        $data = $this->validar($request, true);

        $data['user_id'] = Auth::id();
        $data['solucionado'] = $data['estado'] === 'resuelto';
        $data['tiempo_empleado_minutos'] = BitacoraActividad::calcularMinutos($data['hora_inicio'] ?? null, $data['hora_fin'] ?? null);

        $avancePlan = (int) ($data['avance_plan'] ?? 0);
        unset($data['avance_plan']);

        if ($request->hasFile('evidencia')) {
            $data['evidencia'] = $request->file('evidencia')->store('bitacora_evidencias', 'local');
        }

        try {
            DB::transaction(function () use ($data, $avancePlan) {
                $bitacora = BitacoraActividad::create($data);
                $this->registrarAvancePlan($bitacora, $avancePlan);
            });
        } catch (ValidationException $e) {
            $this->eliminarArchivo($data['evidencia'] ?? null);
            throw $e;
        }

        return redirect()->route('bitacora.index')->with('success', $avancePlan
            ? "Actividad registrada y {$avancePlan}% de avance sumado al Plan de Trabajo."
            : 'Actividad registrada correctamente.');
    }

    /**
     * Editar registro.
     */
    public function edit($id)
    {
        $actividad = BitacoraActividad::with(['planActividad.plan', 'ejecucionPlan.actividad.plan'])->findOrFail($id);

        return view('bitacora.edit', [
            'actividad' => $actividad,
            'actividadesPlan' => $this->actividadesPlanDisponibles($actividad->plan_actividad_id),
        ]);
    }

    /**
     * Actualizar.
     */
    public function update(Request $request, $id)
    {
        $actividad = BitacoraActividad::with('ejecucionPlan')->findOrFail($id);

        // Solo campos validados: user_id, solucionado, etc. no llegan desde el formulario
        $data = $this->validar($request, false);

        $data['solucionado'] = $data['estado'] === 'resuelto';
        $data['tiempo_empleado_minutos'] = BitacoraActividad::calcularMinutos($data['hora_inicio'] ?? null, $data['hora_fin'] ?? null);

        $avancePlan = (int) ($data['avance_plan'] ?? 0);
        unset($data['avance_plan']);

        // Si ya sumó avance a una actividad del plan, el vínculo queda fijo
        if ($actividad->ejecucionPlan) {
            $data['plan_actividad_id'] = $actividad->plan_actividad_id;
            $avancePlan = 0;
        }

        $nuevaEvidencia = $request->hasFile('evidencia')
            ? $request->file('evidencia')->store('bitacora_evidencias', 'local')
            : null;

        if ($nuevaEvidencia) {
            $data['evidencia'] = $nuevaEvidencia;
        }

        $anterior = $actividad->evidencia;

        try {
            DB::transaction(function () use ($actividad, $data, $avancePlan) {
                $actividad->update($data);
                $this->registrarAvancePlan($actividad, $avancePlan);
            });
        } catch (ValidationException $e) {
            $this->eliminarArchivo($nuevaEvidencia);
            throw $e;
        }

        if ($nuevaEvidencia) {
            $this->eliminarArchivo($anterior);
        }

        return redirect()->route('bitacora.show', $actividad->id)
            ->with('success', 'Actividad actualizada correctamente.');
    }

    public function show($id)
    {
        $actividad = BitacoraActividad::with(['user', 'planActividad.plan', 'ejecucionPlan'])->findOrFail($id);

        return view('bitacora.show', compact('actividad'));
    }

    public function evidencia($id)
    {
        return $this->mostrarArchivo(BitacoraActividad::findOrFail($id)->evidencia);
    }

    private function validar(Request $request, bool $nuevo): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'equipo_afectado' => 'nullable|string|max:255',
            'tipo_falla' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'fecha' => ($nuevo ? 'required' : 'sometimes|required') . '|date',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => [
                'nullable', 'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->hora_inicio && strtotime($value) <= strtotime($request->hora_inicio)) {
                        $fail('La hora de fin debe ser posterior a la hora de inicio.');
                    }
                },
            ],
            'solucion_aplicada' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'prioridad' => 'required|string|in:' . implode(',', array_keys(BitacoraActividad::PRIORIDADES)),
            'estado' => 'required|string|in:' . implode(',', array_keys(BitacoraActividad::ESTADOS)),
            'evidencia' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'plan_actividad_id' => 'nullable|integer|exists:plan_actividades,id',
            'avance_plan' => 'nullable|integer|min:1|max:100',
        ], [
            'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:MM.',
        ]);
    }

    /** Suma el avance a la actividad del plan, con esta bitácora como evidencia. */
    private function registrarAvancePlan(BitacoraActividad $bitacora, int $avance): void
    {
        if ($avance < 1) {
            return;
        }

        if (!$bitacora->plan_actividad_id) {
            throw ValidationException::withMessages([
                'avance_plan' => 'Para sumar avance, selecciona la actividad del Plan de Trabajo.',
            ]);
        }

        $this->avances->registrar($bitacora->planActividad()->firstOrFail(), [
            'avance' => $avance,
            'comentario' => trim("Bitácora: {$bitacora->titulo}. {$bitacora->solucion_aplicada}"),
            'fecha' => $bitacora->fecha->toDateString(),
            'user_id' => Auth::id(),
            'bitacora_actividad_id' => $bitacora->id,
        ], 'avance_plan');
    }

    /**
     * Actividades de los planes aprobados (el vigente y el anterior) que aún no
     * llegan al 100%, más la que ya tenga vinculada la bitácora que se edita.
     */
    private function actividadesPlanDisponibles(?int $incluir = null)
    {
        $vigente = PlanTrabajo::vigente();

        if (!$vigente && !$incluir) {
            return collect();
        }

        return PlanActividad::with('plan')
            ->withSum('ejecuciones', 'avance')
            ->where(function ($q) use ($vigente, $incluir) {
                $q->when($vigente, fn ($q) => $q->whereHas('plan', fn ($p) => $p->where('estado', 'aprobado')
                    ->where('anio', '>=', $vigente->anio - 1)))
                    ->when($incluir, fn ($q) => $q->orWhere('id', $incluir));
            })
            ->get()
            ->filter(fn ($a) => $a->progreso < 100 || $a->id === $incluir)
            ->sort(fn ($a, $b) => [$b->plan->anio, $a->seccion] <=> [$a->plan->anio, $b->seccion]
                ?: strnatcmp((string) $a->codigo, (string) $b->codigo))
            ->groupBy(fn ($a) => "Plan {$a->plan->anio} · {$a->seccion}");
    }
}
