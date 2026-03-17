<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesPeriodo;
use App\Models\RrhhVacacionesMovimiento;
use Carbon\Carbon;
use App\Models\RrhhVacacionesBitacora;
use Illuminate\Support\Facades\Auth;

class RrhhVacacionesController extends Controller
{

    public function index(Request $request)
    {
        $anio = $request->anio ?? date('Y');

        $query = RrhhEmpleado::where('estado', 'activo')
            ->with(['periodosVacaciones' => function ($q) use ($anio) {
                $q->where('anio', $anio)
                    ->with('movimientos');
            }]);

        // 🔍 BUSQUEDA
        if ($request->filled('search')) {
            $query->where('nombre_completo', 'like', '%' . $request->search . '%');
        }

        // 📌 AREA
        if ($request->filled('area')) {
            $query->where('area', 'like', '%' . $request->area . '%');
        }

        $empleados = $query->orderBy('nombre_completo')->get();

        // 🔥 CALCULOS + FILTRO DE PENDIENTES
        $empleados = $empleados->map(function ($empleado) use ($anio) {

            $periodo = $empleado->periodosVacaciones->where('anio', $anio)->first();

            $vacaciones = $empleado->vacacionesPorLey();

            $acumulado = $periodo
                ? $periodo->acumulado_anterior
                : $empleado->vacaciones_acumuladas ?? 0;

            $tomado = $periodo ? $periodo->total_tomado : 0;

            $pendiente = $vacaciones + $acumulado - $tomado;

            $empleado->pendiente_calculado = $pendiente;
            $empleado->periodo_actual = $periodo;

            return $empleado;
        });

        // 🎯 FILTRO POR PENDIENTE
        if ($request->filled('pendiente')) {

            $empleados = $empleados->filter(function ($emp) use ($request) {

                if ($request->pendiente == 'con') return $emp->pendiente_calculado > 0;
                if ($request->pendiente == 'sin') return $emp->pendiente_calculado <= 0;
                if ($request->pendiente == 'critico') return $emp->pendiente_calculado > 15;

                return true;
            });
        }

        // 📊 DASHBOARD
        $totalEmpleados = $empleados->count();
        $totalPendiente = $empleados->sum('pendiente_calculado');
        $criticos = $empleados->where('pendiente_calculado', '>', 15)->count();

        return view('rrhh.vacaciones.index', compact(
            'empleados',
            'anio',
            'totalEmpleados',
            'totalPendiente',
            'criticos'
        ));
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

        $bitacora = \App\Models\RrhhVacacionesBitacora::where('empleado_id', $empleado_id)
            ->orderBy('fecha_evento', 'desc')
            ->get();

        return view(
            'rrhh.vacaciones.historial',
            compact('empleado', 'movimientos', 'bitacora')
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

        /*
GUARDAR VALORES ANTERIORES PARA BITACORA
*/

        $diasAnterior = $movimiento->dias_equivalentes;
        $horasAnterior = $movimiento->horas_equivalentes;

        $movimiento->dias_equivalentes = $request->dias_equivalentes;
        $movimiento->horas_equivalentes = $request->horas_equivalentes;
        $movimiento->motivo = $request->motivo;

        $movimiento->save();
        RrhhVacacionesBitacora::create([
            'empleado_id' => $movimiento->empleado_id,
            'movimiento_id' => $movimiento->id,
            'accion' => 'EDICION_MOVIMIENTO',
            'detalle' => "Ajuste manual: días $diasAnterior → {$request->dias_equivalentes}, horas $horasAnterior → {$request->horas_equivalentes}",
            'usuario' => Auth::user()->name ?? 'Sistema'
        ]);
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

    public function acreditarVacacionesPorAntiguedad($empleado_id)
    {
        $empleado = RrhhEmpleado::findOrFail($empleado_id);

        $anio = now()->year;

        /*
    VALIDAR SI YA EXISTE PERIODO (CLAVE)
    */

        $periodoExistente = RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)
            ->where('anio', $anio)
            ->first();

        if ($periodoExistente) {
            return $periodoExistente; // 🔴 YA EXISTE → NO HACER NADA
        }

        /*
    CALCULAR DIAS SEGUN LEY
    */

        $vacacionesLey = $empleado->vacacionesPorLey();

        $acumulado = $empleado->vacaciones_acumuladas ?? 0;

        $periodo = RrhhVacacionesPeriodo::create([
            'empleado_id' => $empleado->id,
            'anio' => $anio,
            'dias_correspondientes' => $vacacionesLey,
            'acumulado_anterior' => $acumulado,
            'dias_descontados' => 0,
            'total_disponible' => $vacacionesLey + $acumulado,
            'total_tomado' => 0,
            'dias_pendientes' => $vacacionesLey + $acumulado
        ]);

        /*
    REGISTRAR BITACORA SOLO UNA VEZ
    */

        \App\Models\RrhhVacacionesBitacora::create([
            'empleado_id' => $empleado->id,
            'movimiento_id' => null,
            'accion' => 'ACREDITACION_ANUAL',
            'detalle' => "Se acreditaron {$vacacionesLey} días por antigüedad (Año {$anio})",
            'usuario' => 'Sistema'
        ]);

        return $periodo;
    }
}
