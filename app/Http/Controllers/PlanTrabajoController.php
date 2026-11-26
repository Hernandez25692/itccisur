<?php

namespace App\Http\Controllers;

use App\Models\PlanActividad;
use App\Models\PlanRevision;
use App\Models\PlanTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlanTrabajoController extends Controller
{
    public function index(Request $request)
    {
        $planes = PlanTrabajo::query()
            ->with(['actividades' => fn ($q) => $q->select('id', 'plan_trabajo_id', 'mes_previsto')
                ->withSum('ejecuciones', 'avance')])
            ->withCount('actividades')
            ->when($request->anio, fn ($q, $anio) => $q->where('anio', $anio))
            ->when($request->estado, fn ($q, $estado) => $q->where('estado', $estado))
            ->orderBy('anio', 'DESC')
            ->get();

        // Los contadores no dependen del filtro
        $porEstado = PlanTrabajo::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $anios = PlanTrabajo::orderByDesc('anio')->pluck('anio')->unique()->values();

        return view('plan_trabajo.index', compact('planes', 'porEstado', 'anios'));
    }

    public function create()
    {
        return view('plan_trabajo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer|min:2024|max:2100|unique:plan_trabajos,anio',
            'descripcion_general' => 'nullable|string'
        ], [
            'anio.unique' => 'Ya existe un plan para ese año. Si fue rechazado, corrígelo y vuelve a enviarlo.',
        ]);

        PlanTrabajo::create([
            'anio' => $request->anio,
            'descripcion_general' => $request->descripcion_general,
            'estado' => 'borrador',
        ]);

        return redirect()->route('plan-trabajo.index')
            ->with('success', 'Plan de Trabajo creado correctamente.');
    }

    public function show(Request $request, PlanTrabajo $plan)
    {
        $plan->load([
            'actividades' => fn ($q) => $q->withSum('ejecuciones', 'avance')->withCount('bitacoras'),
            'revisiones.usuario',
        ]);

        // Orden natural del código dentro de cada sección ("2" antes de "10")
        $todas = $plan->actividades
            ->sort(fn ($a, $b) => strcmp($a->seccion, $b->seccion) ?: strnatcmp((string) $a->codigo, (string) $b->codigo))
            ->values();

        $resumen = [
            'total' => $todas->count(),
            'finalizadas' => $todas->where('progreso', '>=', 100)->count(),
            'en_progreso' => $todas->filter(fn ($a) => $a->progreso > 0 && $a->progreso < 100)->count(),
            'pendientes' => $todas->where('progreso', 0)->count(),
            'atrasadas' => $todas->filter->atrasada->count(),
            'avance' => $plan->avance_global,
            'bitacoras' => $todas->sum('bitacoras_count'),
        ];

        $filtros = $request->only(['buscar', 'seccion', 'situacion', 'responsable', 'mes']);

        $actividades = $todas->filter(function (PlanActividad $a) use ($filtros) {
            $texto = mb_strtolower($filtros['buscar'] ?? '');

            return (!$texto || str_contains(mb_strtolower($a->actividad . ' ' . $a->objetivo), $texto))
                && (empty($filtros['seccion']) || $a->seccion === $filtros['seccion'])
                && (empty($filtros['responsable']) || $a->responsable === $filtros['responsable'])
                && (empty($filtros['mes']) || $a->mes_previsto_numero === (int) $filtros['mes'])
                && match ($filtros['situacion'] ?? '') {
                    'pendiente' => $a->progreso === 0,
                    'en_progreso' => $a->progreso > 0 && $a->progreso < 100,
                    'finalizado' => $a->progreso >= 100,
                    'atrasada' => $a->atrasada,
                    default => true,
                };
        });

        $secciones = $todas->pluck('seccion')->unique()->values();
        $responsables = $todas->pluck('responsable')->filter()->unique()->sort()->values();

        // Numeración "sección.actividad" sobre el plan completo, para que no cambie al filtrar
        $numeracion = [];
        foreach ($todas->groupBy('seccion')->values() as $s => $grupo) {
            foreach ($grupo->values() as $i => $a) {
                $numeracion[$a->id] = ($s + 1) . '.' . ($i + 1);
            }
        }
        $numeroSeccion = $secciones->flip()->map(fn ($i) => $i + 1);

        return view('plan_trabajo.show', compact(
            'plan', 'actividades', 'resumen', 'filtros', 'secciones', 'responsables', 'numeracion', 'numeroSeccion'
        ));
    }

    public function edit(PlanTrabajo $plan)
    {
        if (!$plan->esEditable()) {
            return $this->noEditable($plan);
        }

        return view('plan_trabajo.edit', compact('plan'));
    }

    public function update(Request $request, PlanTrabajo $plan)
    {
        if (!$plan->esEditable()) {
            return $this->noEditable($plan);
        }

        $request->validate([
            'anio' => ['required', 'integer', 'min:2024', 'max:2100', Rule::unique('plan_trabajos', 'anio')->ignore($plan->id)],
            'descripcion_general' => 'nullable|string'
        ], [
            'anio.unique' => 'Ya existe otro plan para ese año.',
        ]);

        $plan->update($request->only('anio', 'descripcion_general'));

        return redirect()->route('plan-trabajo.show', $plan)
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function enviar(PlanTrabajo $plan)
    {
        if (!$plan->esEditable()) {
            return $this->noEditable($plan);
        }

        if (!$plan->actividades()->exists()) {
            return redirect()->route('plan-trabajo.show', $plan)
                ->with('error', 'Agrega al menos una actividad antes de enviar el plan a Gerencia.');
        }

        DB::transaction(function () use ($plan) {
            PlanRevision::create([
                'plan_trabajo_id' => $plan->id,
                'user_id' => auth()->id(),
                'accion' => 'enviado',
                'comentario' => null,
            ]);

            $plan->update([
                'estado' => 'enviado',
                'comentario_gerencia' => null, // Limpia comentario previo
                'fecha_aprobacion' => null,    // Limpia fecha de revisión anterior
            ]);
        });

        return redirect()->route('plan-trabajo.show', $plan->id)
            ->with('success', 'Plan enviado a Gerencia para revisión.');
    }

    public function aprobar(Request $request, PlanTrabajo $plan)
    {
        return $this->revisar($request, $plan, 'aprobado', 'nullable|string');
    }

    public function rechazar(Request $request, PlanTrabajo $plan)
    {
        return $this->revisar($request, $plan, 'rechazado', 'required|string');
    }

    /** Gerencia solo revisa planes que TI envió. */
    private function revisar(Request $request, PlanTrabajo $plan, string $accion, string $reglaComentario)
    {
        if ($plan->estado !== 'enviado') {
            return redirect()->route('plan-trabajo.show', $plan)
                ->with('error', 'Solo se puede aprobar o rechazar un plan que TI haya enviado a revisión.');
        }

        $request->validate(['comentario_gerencia' => $reglaComentario]);

        DB::transaction(function () use ($request, $plan, $accion) {
            PlanRevision::create([
                'plan_trabajo_id' => $plan->id,
                'user_id' => auth()->id(),
                'accion' => $accion,
                'comentario' => $request->comentario_gerencia,
            ]);

            $plan->update([
                'estado' => $accion,
                'comentario_gerencia' => $request->comentario_gerencia,
                'aprobado_por' => auth()->id(),
                'fecha_aprobacion' => now(),
            ]);
        });

        return redirect()->route('plan-trabajo.show', $plan)->with(
            'success',
            $accion === 'aprobado' ? 'Plan aprobado correctamente.' : 'Plan rechazado. Se registró el comentario de gerencia.'
        );
    }

    private function noEditable(PlanTrabajo $plan)
    {
        return redirect()->route('plan-trabajo.show', $plan)
            ->with('error', "El plan está {$plan->estado}: solo se modifica en borrador o rechazado.");
    }
}
