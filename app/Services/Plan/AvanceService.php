<?php

namespace App\Services\Plan;

use App\Models\ActividadEjecucion;
use App\Models\PlanActividad;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Registro de avances del Plan de Trabajo. Lo usan el formulario de la
 * actividad y la Bitácora TI, para que ambos respeten el mismo tope de 100%.
 */
class AvanceService
{
    public function restante(PlanActividad $actividad): int
    {
        return max(0, 100 - min(100, (int) $actividad->ejecuciones()->sum('avance')));
    }

    /**
     * @param  array{avance:int, comentario:?string, fecha:string, evidencia?:?string,
     *               user_id:int, bitacora_actividad_id?:?int}  $datos
     * @param  string  $campo  Campo del formulario al que se asigna el error.
     *
     * @throws ValidationException si la actividad ya está completa o el avance excede lo restante.
     */
    public function registrar(PlanActividad $actividad, array $datos, string $campo = 'avance'): ActividadEjecucion
    {
        return DB::transaction(function () use ($actividad, $datos, $campo) {
            // Bloquea la fila para que dos avances simultáneos no pasen del 100%
            $actividad = PlanActividad::whereKey($actividad->getKey())->lockForUpdate()->firstOrFail();
            $restante = $this->restante($actividad);
            $avance = (int) $datos['avance'];

            if ($restante === 0) {
                throw ValidationException::withMessages([
                    $campo => 'Esta actividad ya está completada y no puede recibir más avances.',
                ]);
            }

            if ($avance < 1 || $avance > $restante) {
                throw ValidationException::withMessages([
                    $campo => "El avance debe estar entre 1% y {$restante}% (lo que le falta a la actividad).",
                ]);
            }

            $ejecucion = ActividadEjecucion::create([
                'plan_actividad_id' => $actividad->id,
                'bitacora_actividad_id' => $datos['bitacora_actividad_id'] ?? null,
                'avance' => $avance,
                'comentario' => $datos['comentario'] ?? null,
                'fecha' => $datos['fecha'],
                'evidencia' => $datos['evidencia'] ?? null,
                'user_id' => $datos['user_id'],
            ]);

            $actividad->update([
                'estado' => PlanActividad::estadoSegunProgreso(100 - $restante + $avance),
            ]);

            return $ejecucion;
        });
    }
}
