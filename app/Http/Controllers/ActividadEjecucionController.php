<?php

namespace App\Http\Controllers;

use App\Models\ActividadEjecucion;
use App\Models\PlanActividad;
use App\Services\Plan\AvanceService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActividadEjecucionController extends Controller
{
    public function store(Request $request, PlanActividad $plan_actividad, AvanceService $avances)
    {
        $datos = $request->validate([
            'avance' => 'required|integer|min:1|max:100',
            'comentario' => 'required|string',
            'fecha' => 'required|date',
            'evidencia' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:4096',
        ]);

        $path = $request->hasFile('evidencia')
            ? $request->file('evidencia')->store('evidencias', 'local')
            : null;

        try {
            $avances->registrar($plan_actividad, [
                'avance' => $datos['avance'],
                'comentario' => $datos['comentario'],
                'fecha' => $datos['fecha'],
                'evidencia' => $path,
                'user_id' => auth()->id(),
            ]);
        } catch (ValidationException $e) {
            $this->eliminarArchivo($path);
            throw $e;
        }

        return back()->with('success', 'Avance registrado correctamente.');
    }

    public function evidencia(ActividadEjecucion $ejecucion)
    {
        return $this->mostrarArchivo($ejecucion->evidencia);
    }
}
