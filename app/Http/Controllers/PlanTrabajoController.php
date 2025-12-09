<?php

namespace App\Http\Controllers;

use App\Models\PlanTrabajo;
use Illuminate\Http\Request;
use App\Models\PlanRevision;

class PlanTrabajoController extends Controller
{
    public function index()
    {
        $planes = PlanTrabajo::withCount('actividades')
            ->orderBy('anio', 'DESC')
            ->get();

        return view('plan_trabajo.index', compact('planes'));
    }

    public function create()
    {
        return view('plan_trabajo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer|min:2024|max:2100',
            'descripcion_general' => 'nullable|string'
        ]);

        PlanTrabajo::create([
            'anio' => $request->anio,
            'descripcion_general' => $request->descripcion_general,
            'estado' => 'borrador',
        ]);

        return redirect()->route('plan-trabajo.index')
            ->with('success', 'Plan de Trabajo creado correctamente.');
    }

    public function show(PlanTrabajo $plan)
    {
        $plan->load(['actividades' => function ($q) {
            $q->orderBy('seccion', 'ASC')
                ->orderBy('codigo', 'ASC');
        }]);



        return view('plan_trabajo.show', compact('plan'));
    }

    public function edit(PlanTrabajo $plan)
    {
        return view('plan_trabajo.edit', compact('plan'));
    }

    public function update(Request $request, PlanTrabajo $plan)
    {
        $request->validate([
            'anio' => 'required|integer|min:2024|max:2100',
            'descripcion_general' => 'nullable|string'
        ]);

        $plan->update($request->only('anio', 'descripcion_general'));

        return redirect()->route('plan-trabajo.show', $plan)
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function enviar(PlanTrabajo $plan)
    {
        if ($plan->estado === 'borrador' || $plan->estado === 'rechazado') {
            $plan->update([
                'estado' => 'enviado',
                'comentario_gerencia' => null, // Limpia comentario previo
                'fecha_aprobacion' => null,    // Limpia fecha de revisión anterior
            ]);
        }

        return redirect()->route('plan-trabajo.show', $plan->id)
            ->with('success', 'Plan enviado a Gerencia para revisión.');
    }

    public function aprobar(Request $request, PlanTrabajo $plan)
    {
        $request->validate([
            'comentario_gerencia' => 'nullable|string',
        ]);

        PlanRevision::create([
            'plan_trabajo_id' => $plan->id,
            'user_id' => auth()->id(),
            'accion' => 'aprobado',
            'comentario' => $request->comentario_gerencia,
        ]);

        $plan->update([
            'estado' => 'aprobado',
            'comentario_gerencia' => $request->comentario_gerencia,
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now(),
        ]);

        return redirect()->route('plan-trabajo.show', $plan)
            ->with('success', 'Plan aprobado correctamente.');
    }



    public function rechazar(Request $request, PlanTrabajo $plan)
    {
        $request->validate([
            'comentario_gerencia' => 'required|string',
        ]);

        // Guardar historial
        PlanRevision::create([
            'plan_trabajo_id' => $plan->id,
            'user_id' => auth()->id(),
            'accion' => 'rechazado',
            'comentario' => $request->comentario_gerencia,
        ]);

        // Actualizar estado actual
        $plan->update([
            'estado' => 'rechazado',
            'comentario_gerencia' => $request->comentario_gerencia,
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now(),
        ]);

        return redirect()->route('plan-trabajo.show', $plan)
            ->with('success', 'Plan rechazado. Se registró el comentario de gerencia.');
    }
}
