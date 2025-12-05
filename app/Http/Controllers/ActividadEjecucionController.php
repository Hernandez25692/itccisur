<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanActividad;
use App\Models\ActividadEjecucion;

class ActividadEjecucionController extends Controller
{
    public function store(Request $request, PlanActividad $plan_actividad)
    {
        $actividad = $plan_actividad; // alias para mantener coherencia

        $request->validate([
            'avance' => 'required|numeric|min:0|max:100',
            'comentario' => 'required|string',
            'fecha' => 'required|date',
            'evidencia' => 'nullable|file|mimes:pdf,jpg,png,docx'
        ]);

        $path = null;
        if ($request->hasFile('evidencia')) {
            $path = $request->file('evidencia')->store('evidencias', 'public');
        }

        ActividadEjecucion::create([
            'plan_actividad_id' => $actividad->id,
            'avance' => $request->avance,
            'comentario' => $request->comentario,
            'fecha' => $request->fecha,
            'evidencia' => $path,
            'user_id' => auth()->id(),
        ]);

        if ($request->avance == 100) {
            $actividad->update(['estado' => 'finalizado']);
        } elseif ($request->avance > 0) {
            $actividad->update(['estado' => 'en_progreso']);
        }

        return back()->with('success', 'Avance registrado correctamente.');
    }
}
