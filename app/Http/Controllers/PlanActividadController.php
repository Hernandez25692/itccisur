<?php

namespace App\Http\Controllers;

use App\Models\PlanActividad;
use App\Models\PlanTrabajo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanActividadController extends Controller
{
    private const REGLAS = [
        'seccion' => 'required|string|max:255',
        'actividad' => 'required|string|max:255',
        'objetivo' => 'required|string',
        'frecuencia' => 'required|string|max:100',
        'responsable' => 'required|string|max:255',
        'mes_previsto' => 'nullable|string|max:50',
        'fecha_ejecucion' => 'nullable|string|max:100',
        'metrica_exito' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
    ];

    /**
     * Mostrar formulario para crear una nueva actividad dentro de un Plan de Trabajo.
     */
    public function create(PlanTrabajo $plan)
    {
        if ($respuesta = $this->siNoEditable($plan)) {
            return $respuesta;
        }

        return view('plan_actividades.create', compact('plan'));
    }

    /**
     * Guardar actividad en la base de datos.
     */
    public function store(Request $request, PlanTrabajo $plan)
    {
        if ($respuesta = $this->siNoEditable($plan)) {
            return $respuesta;
        }

        $datos = $request->validate(self::REGLAS);

        // codigo es texto: el máximo se saca como número (si no, "9" > "10")
        $ultimo = $plan->actividades()->pluck('codigo')->map(fn ($c) => (int) $c)->max() ?? 0;

        $plan->actividades()->create($datos + ['codigo' => (string) ($ultimo + 1)]);

        return redirect()->route('plan-trabajo.show', $plan->id)
            ->with('success', 'Actividad agregada correctamente.');
    }

    public function show(PlanActividad $actividad)
    {
        $actividad->load([
            'plan',
            'ejecuciones.usuario',
            'ejecuciones.bitacora',
            'bitacoras.user',
        ]);

        return view('plan_actividades.show', compact('actividad'));
    }

    public function edit(PlanActividad $actividad)
    {
        if ($respuesta = $this->siNoEditable($actividad->plan, $actividad)) {
            return $respuesta;
        }

        return view('plan_actividades.edit', compact('actividad'));
    }

    public function update(Request $request, PlanActividad $actividad)
    {
        if ($respuesta = $this->siNoEditable($actividad->plan, $actividad)) {
            return $respuesta;
        }

        // El estado ya no se edita a mano: sale del progreso de los avances
        $datos = $request->validate(self::REGLAS + [
            'codigo' => [
                'required', 'string', 'max:10',
                Rule::unique('plan_actividades', 'codigo')
                    ->where('plan_trabajo_id', $actividad->plan_trabajo_id)
                    ->ignore($actividad->id),
            ],
        ]);

        $actividad->update($datos);

        return redirect()->route('plan-trabajo.show', $actividad->plan_trabajo_id)
            ->with('success', 'Actividad actualizada correctamente.');
    }

    /**
     * Agregar actividades: solo en borrador o rechazado (como ya lo mostraba la vista).
     * Editar una existente: en cualquier estado menos "enviado", para que gerencia
     * no revise un plan que cambia mientras lo lee.
     */
    private function siNoEditable(PlanTrabajo $plan, ?PlanActividad $actividad = null)
    {
        $permitido = $actividad ? $plan->estado !== 'enviado' : $plan->esEditable();

        if ($permitido) {
            return null;
        }

        $destino = $actividad
            ? route('plan-actividad.show', $actividad)
            : route('plan-trabajo.show', $plan);

        $mensaje = $actividad
            ? 'El plan está en revisión de gerencia: espera la aprobación o el rechazo para editar sus actividades.'
            : "El plan está {$plan->estado}: solo se agregan actividades en borrador o rechazado.";

        return redirect($destino)->with('error', $mensaje);
    }
}
