<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesPeriodo;
use App\Models\RrhhVacacionesMovimiento;
use Carbon\Carbon;

class RrhhVacacionesController extends Controller
{

    public function index(Request $request)
    {

        $anio = $request->anio ?? date('Y');

        $empleados = RrhhEmpleado::where('estado', 'activo')
            ->with(['periodosVacaciones' => function ($q) use ($anio) {
                $q->where('anio', $anio)
                    ->with('movimientos');
            }])
            ->orderBy('nombre_completo')
            ->get();

        return view('rrhh.vacaciones.index', compact('empleados', 'anio'));
    }


    public function crearSolicitud($empleado_id)
    {

        $empleado = RrhhEmpleado::findOrFail($empleado_id);

        return view('rrhh.vacaciones.crear_solicitud', compact('empleado'));
    }


    public function guardarSolicitud(Request $request)
    {

        $request->validate([
            'empleado_id' => 'required',
            'tipo_movimiento' => 'required',
            'fecha_inicio' => 'required|date'
        ]);

        $horas = 0;
        $dias = 0;

        if ($request->tipo_movimiento == 'vacacion_horas') {

            if ($request->hora_inicio && $request->hora_fin) {

                $inicio = Carbon::parse($request->hora_inicio);
                $fin = Carbon::parse($request->hora_fin);

                $horas = $inicio->diffInMinutes($fin) / 60;

                $dias = $horas / 8;
            }
        }

        if ($request->tipo_movimiento == 'permiso_especial') {

            if ($request->hora_inicio && $request->hora_fin) {

                $inicio = Carbon::parse($request->hora_inicio);
                $fin = Carbon::parse($request->hora_fin);

                $horas = $inicio->diffInMinutes($fin) / 60;

                $dias = $horas / 8;
            } else {

                $dias = 1;
                $horas = 8;
            }
        }

        if ($request->tipo_movimiento == 'vacacion_dias') {

            $inicio = Carbon::parse($request->fecha_inicio);
            $fin = Carbon::parse($request->fecha_fin);

            $dias = $inicio->diffInDaysFiltered(function (Carbon $date) {
                return $date->dayOfWeek !== Carbon::SUNDAY;
            }, $fin) + 1;
        }

        /*
        AJUSTE MANUAL DEL ADMIN
        */

        if ($request->filled('dias_ajuste')) {

            $dias = $request->dias_ajuste;
            $horas = $dias * 8;
        }

        $mes = Carbon::parse($request->fecha_inicio)->format('m');

        $anio = Carbon::parse($request->fecha_inicio)->format('Y');

        $empleado = RrhhEmpleado::findOrFail($request->empleado_id);

        $vacacionesLey = $empleado->vacacionesPorLey();
        $acumuladoInicial = $empleado->vacaciones_acumuladas ?? 0;

        $periodo = RrhhVacacionesPeriodo::firstOrCreate(
            [
                'empleado_id' => $request->empleado_id,
                'anio' => $anio
            ],
            [
                'dias_correspondientes' => $vacacionesLey,
                'acumulado_anterior' => $acumuladoInicial,
                'dias_descontados' => 0,
                'total_disponible' => $vacacionesLey + $acumuladoInicial,
                'total_tomado' => 0,
                'dias_pendientes' => $vacacionesLey + $acumuladoInicial
            ]
        );
        /*
ACTUALIZAR DIAS SEGÚN ANTIGÜEDAD REAL
*/

        if ($periodo->dias_correspondientes != $vacacionesLey) {

            $periodo->dias_correspondientes = $vacacionesLey;

            $periodo->total_disponible =
                $vacacionesLey + $periodo->acumulado_anterior;

            $periodo->dias_pendientes =
                $periodo->total_disponible - $periodo->total_tomado;

            $periodo->save();
        }


        $movimiento = new RrhhVacacionesMovimiento();

        $movimiento->empleado_id = $request->empleado_id;
        $movimiento->periodo_vacacion_id = $periodo->id;
        $movimiento->tipo_movimiento = $request->tipo_movimiento;
        $movimiento->fecha_inicio = $request->fecha_inicio;
        $movimiento->fecha_fin = $request->fecha_fin;
        $movimiento->hora_inicio = $request->hora_inicio;
        $movimiento->hora_fin = $request->hora_fin;
        $movimiento->dias_equivalentes = $dias;
        $movimiento->horas_equivalentes = $horas;
        $movimiento->mes_referencia = $mes;
        $movimiento->motivo = $request->motivo;
        $movimiento->estado = 'aprobado';

        if ($request->hasFile('archivo')) {

            $archivo = $request->file('archivo')->store('vacaciones');

            $movimiento->archivo_comprobante = $archivo;
        }

        $movimiento->save();

        /*
        ACTUALIZAR SALDOS
        */

        if ($request->tipo_movimiento != 'permiso_especial') {

            $periodo->total_tomado += $dias;

            $periodo->dias_pendientes =
                ($periodo->dias_correspondientes + $periodo->acumulado_anterior)
                - $periodo->total_tomado;

            $periodo->save();
        }

        return redirect()
            ->route('vacaciones.index')
            ->with('success', 'Solicitud registrada correctamente');
    }


    public function historial($empleado_id)
    {

        $empleado = RrhhEmpleado::findOrFail($empleado_id);

        $movimientos = RrhhVacacionesMovimiento::where('empleado_id', $empleado_id)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view(
            'rrhh.vacaciones.historial',
            compact('empleado', 'movimientos')
        );
    }


    public function editarMovimiento($id)
    {

        $movimiento = RrhhVacacionesMovimiento::findOrFail($id);

        return view(
            'rrhh.vacaciones.editar_movimiento',
            compact('movimiento')
        );
    }


    public function actualizarMovimiento(Request $request, $id)
    {

        $request->validate([
            'dias_equivalentes' => 'nullable|numeric',
            'horas_equivalentes' => 'nullable|numeric',
        ]);

        $movimiento = RrhhVacacionesMovimiento::findOrFail($id);

        $movimiento->dias_equivalentes = $request->dias_equivalentes;
        $movimiento->horas_equivalentes = $request->horas_equivalentes;
        $movimiento->motivo = $request->motivo;

        $movimiento->save();

        /*
        RECALCULAR PERIODO
        */

        $periodo = RrhhVacacionesPeriodo::find($movimiento->periodo_vacacion_id);

        $total = RrhhVacacionesMovimiento::where('periodo_vacacion_id', $periodo->id)
            ->where('tipo_movimiento', '!=', 'permiso_especial')
            ->sum('dias_equivalentes');

        $periodo->total_tomado = $total;

        $periodo->dias_pendientes =
            ($periodo->dias_correspondientes + $periodo->acumulado_anterior)
            - $total;

        $periodo->save();

        return redirect()
            ->route('vacaciones.historial', $movimiento->empleado_id)
            ->with('success', 'Movimiento actualizado correctamente');
    }
}
