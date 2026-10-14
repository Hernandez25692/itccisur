<?php

namespace App\Http\Controllers;

use App\Services\Rrhh\VacacionesService;
use Illuminate\Support\Facades\Auth;

class RrhhVacacionesPeriodoController extends Controller
{
    /**
     * Acredita los días por aniversario pendientes de todos los empleados.
     * Es el mismo proceso que corre a diario el comando rrhh:acreditar-vacaciones;
     * se puede ejecutar varias veces sin duplicar acreditaciones.
     */
    public function procesar(VacacionesService $vacaciones)
    {
        $resultado = $vacaciones->sincronizarTodos(null, Auth::user()?->name ?? 'Sistema');

        $mensaje = $resultado['acreditaciones'] > 0
            ? "Se acreditaron {$resultado['acreditaciones']} periodo(s) de vacaciones a {$resultado['empleados']} empleado(s)."
            : 'No había acreditaciones pendientes; los saldos fueron recalculados.';

        return back()->with('success', $mensaje);
    }
}
