<?php

namespace App\Http\Controllers;

use App\Models\PlanTrabajo;
use App\Models\PlanActividad;
use Illuminate\Http\Request;

class PlanActividadController extends Controller
{
    /**
     * Mostrar formulario para crear una nueva actividad dentro de un Plan de Trabajo.
     */
    public function create(PlanTrabajo $plan)
    {
        return view('plan_actividades.create', compact('plan'));
    }

    /**
     * Guardar actividad en la base de datos.
     */
    public function store(Request $request, PlanTrabajo $plan)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
            'seccion' => 'required|string|max:255',
            'actividad' => 'required|string',
            'objetivo' => 'required|string',
            'frecuencia' => 'required|string|max:100',
            'responsable' => 'required|string|max:255',
            'mes_previsto' => 'nullable|string|max:50',
            'fecha_ejecucion' => 'nullable|string|max:100',
            'metrica_exito' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        // Crear la actividad asociada al plan
        $plan->actividades()->create([
            'codigo' => $request->codigo,
            'seccion' => $request->seccion,
            'actividad' => $request->actividad,
            'objetivo' => $request->objetivo,
            'frecuencia' => $request->frecuencia,
            'responsable' => $request->responsable,
            'mes_previsto' => $request->mes_previsto,
            'fecha_ejecucion' => $request->fecha_ejecucion,
            'metrica_exito' => $request->metrica_exito,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('plan-trabajo.show', $plan->id)
            ->with('success', 'Actividad agregada correctamente.');
    }

    public function show(PlanActividad $actividad)
    {
        return view('plan_actividades.show', compact('actividad'));
    }

    public function edit(PlanActividad $actividad)
    {
        return view('plan_actividades.edit', compact('actividad'));
    }

    public function update(Request $request, PlanActividad $actividad)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
            'seccion' => 'required|string|max:255',
            'actividad' => 'required|string',
            'objetivo' => 'required|string',
            'frecuencia' => 'required|string|max:100',
            'responsable' => 'required|string|max:255',
            'mes_previsto' => 'nullable|string|max:50',
            'fecha_ejecucion' => 'nullable|string|max:100',
            'metrica_exito' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:pendiente,en_progreso,finalizado',
        ]);

        $actividad->update($request->all());

        return redirect()->route('plan-trabajo.show', $actividad->plan_trabajo_id)
            ->with('success', 'Actividad actualizada correctamente.');
    }
}
