<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesPeriodo;
use Carbon\Carbon;

class RrhhVacacionesPeriodoController extends Controller
{

    public function generarPeriodos($anio = null)
    {
        $anio = $anio ?? date('Y');

        $empleados = RrhhEmpleado::where('estado', 'activo')->get();

        foreach ($empleados as $empleado) {

            $existe = RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)
                ->where('anio', $anio)
                ->first();

            if (!$existe) {

                $antiguedad = Carbon::parse($empleado->fecha_contratacion)->diffInYears(now());

                $dias = 0;

                if ($antiguedad >= 4) {
                    $dias = 20;
                } elseif ($antiguedad == 3) {
                    $dias = 15;
                } elseif ($antiguedad == 2) {
                    $dias = 12;
                } elseif ($antiguedad == 1) {
                    $dias = 10;
                }

                RrhhVacacionesPeriodo::create([
                    'empleado_id' => $empleado->id,
                    'anio' => $anio,
                    'dias_correspondientes' => $dias,
                    'acumulado_anterior' => 0,
                    'dias_descontados' => 0,
                    'total_disponible' => $dias,
                    'total_tomado' => 0,
                    'dias_pendientes' => $dias
                ]);
            }
        }

        return back()->with('success', 'Periodos de vacaciones generados correctamente');
    }
}
