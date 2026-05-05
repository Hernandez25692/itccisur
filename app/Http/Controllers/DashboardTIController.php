<?php

namespace App\Http\Controllers;

use App\Models\BitacoraActividad;
use App\Models\ControlRecordatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardTIController extends Controller
{
    public function index(Request $request)
    {
        $hoy = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Filtros del Dashboard TI
        |--------------------------------------------------------------------------
        | Si no se envían fechas, se muestra por defecto el mes actual.
        */

        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : $hoy->copy()->startOfMonth();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : $hoy->copy()->endOfDay();

        $estado = $request->estado;
        $prioridad = $request->prioridad;
        $tipoFalla = $request->tipo_falla;

        /*
        |--------------------------------------------------------------------------
        | Query base con filtros
        |--------------------------------------------------------------------------
        */

        $queryBase = BitacoraActividad::query()
            ->whereBetween('fecha', [
                $fechaInicio->toDateString(),
                $fechaFin->toDateString()
            ]);

        if ($estado) {
            $queryBase->where('estado', $estado);
        }

        if ($prioridad) {
            $queryBase->where('prioridad', $prioridad);
        }

        if ($tipoFalla) {
            $queryBase->where('tipo_falla', $tipoFalla);
        }

        /*
        |--------------------------------------------------------------------------
        | Métricas principales
        |--------------------------------------------------------------------------
        */

        $actividadesHoy = (clone $queryBase)->count();

        $pendientes = (clone $queryBase)
            ->where('estado', 'pendiente')
            ->count();

        $resueltasMes = (clone $queryBase)
            ->where('estado', 'resuelto')
            ->count();

        $tiempoPromedio = (clone $queryBase)
            ->whereNotNull('tiempo_empleado_minutos')
            ->avg('tiempo_empleado_minutos');

        $tiempoPromedio = $tiempoPromedio ? round($tiempoPromedio) : 0;

        /*
        |--------------------------------------------------------------------------
        | Gráfica: Distribución por prioridad
        |--------------------------------------------------------------------------
        */

        $prioridades = (clone $queryBase)
            ->select('prioridad', DB::raw('COUNT(*) as total'))
            ->whereNotNull('prioridad')
            ->groupBy('prioridad')
            ->pluck('total', 'prioridad');

        /*
        |--------------------------------------------------------------------------
        | Gráfica: Fallas por tipo
        |--------------------------------------------------------------------------
        */

        $fallasPorTipo = (clone $queryBase)
            ->select('tipo_falla', DB::raw('COUNT(*) as total'))
            ->whereNotNull('tipo_falla')
            ->groupBy('tipo_falla')
            ->pluck('total', 'tipo_falla');

        /*
        |--------------------------------------------------------------------------
        | Gráfica: Actividades por día según rango filtrado
        |--------------------------------------------------------------------------
        */

        $porDiaRaw = (clone $queryBase)
            ->select(DB::raw('DATE(fecha) as dia'), DB::raw('COUNT(*) as total'))
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $porDia = $porDiaRaw->pluck('total', 'dia')->mapWithKeys(function ($value, $key) {
            return [Carbon::parse($key)->format('d/m') => $value];
        });

        /*
        |--------------------------------------------------------------------------
        | Últimas actividades filtradas
        |--------------------------------------------------------------------------
        */

        $ultimas = (clone $queryBase)
            ->with('user')
            ->orderBy('fecha', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Selects dinámicos para filtros
        |--------------------------------------------------------------------------
        */

        $estados = BitacoraActividad::whereNotNull('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado');

        $prioridadesFiltro = BitacoraActividad::whereNotNull('prioridad')
            ->distinct()
            ->orderBy('prioridad')
            ->pluck('prioridad');

        $tiposFallaFiltro = BitacoraActividad::whereNotNull('tipo_falla')
            ->distinct()
            ->orderBy('tipo_falla')
            ->pluck('tipo_falla');

        /*
        |--------------------------------------------------------------------------
        | Alertas de control
        |--------------------------------------------------------------------------
        | Estas no dependen del filtro de actividades.
        */

        $alertas = ControlRecordatorio::where('notificar', true)
            ->where('atendido', false)
            ->whereDate('fecha_vencimiento', '>=', $hoy)
            ->whereRaw('DATEDIFF(fecha_vencimiento, ?) BETWEEN 0 AND dias_recordatorio', [$hoy->toDateString()])
            ->orderBy('fecha_vencimiento', 'ASC')
            ->get();

        return view('dashboard-ti.index', compact(
            'actividadesHoy',
            'pendientes',
            'resueltasMes',
            'tiempoPromedio',
            'prioridades',
            'fallasPorTipo',
            'porDia',
            'ultimas',
            'alertas',
            'fechaInicio',
            'fechaFin',
            'estado',
            'prioridad',
            'tipoFalla',
            'estados',
            'prioridadesFiltro',
            'tiposFallaFiltro'
        ));
    }
}
