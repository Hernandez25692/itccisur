<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanActividad;
use App\Models\ActividadEjecucion;

class ActividadEjecucionController extends Controller
{
    public function store(Request $request, PlanActividad $plan_actividad)
    {
        $actividad = $plan_actividad;

        if ($actividad->progreso >= 100 || $actividad->estado === 'finalizado') {
            return back()->with('error', 'Esta actividad ya está completada y no puede recibir más avances.');
        }

        $request->validate([
            'avance' => 'required|integer|min:1|max:100',
            'comentario' => 'required|string',
            'fecha' => 'required|date',
            'evidencia' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:4096',
        ]);

        $incremento = intval($request->avance);
        $progresoActual = $actividad->progreso;
        $progresoRestante = 100 - $progresoActual;

        if ($incremento > $progresoRestante) {
            return back()->with('error', "El avance ingresado excede el progreso restante. Solo queda disponible un máximo de {$progresoRestante}%.");
        }

        $nuevoProgreso = $progresoActual + $incremento;

        $path = null;
        if ($request->hasFile('evidencia')) {
            $path = $request->file('evidencia')->store('evidencias', 'local');
        }

        ActividadEjecucion::create([
            'plan_actividad_id' => $actividad->id,
            'avance' => $incremento, // solo el incremento
            'comentario' => $request->comentario,
            'fecha' => $request->fecha,
            'evidencia' => $path,
            'user_id' => auth()->id(),
        ]);

        if ($nuevoProgreso >= 100) {
            $actividad->update([
                'progreso' => 100,
                'estado' => 'finalizado'
            ]);
        } else {
            $actividad->update([
                'progreso' => $nuevoProgreso,
                'estado' => 'en_progreso'
            ]);
        }

        return back()->with('success', 'Avance registrado correctamente.');
    }

    public function evidencia(ActividadEjecucion $ejecucion)
    {
        return $this->mostrarArchivo($ejecucion->evidencia);
    }
}
