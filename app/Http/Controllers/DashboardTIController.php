<?php

namespace App\Http\Controllers;

use App\Models\BitacoraActividad;
use App\Models\ControlRecordatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardTiController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // 🔹 Actividades de hoy
        $actividadesHoy = BitacoraActividad::whereDate('fecha', $hoy)->count();

        // 🔹 Pendientes
        $pendientes = BitacoraActividad::where('estado', 'pendiente')->count();

        // 🔹 Resueltas este mes
        $resueltasMes = BitacoraActividad::where('estado', 'resuelto')
            ->whereMonth('fecha', $hoy->month)
            ->whereYear('fecha', $hoy->year)
            ->count();

        // 🔹 Tiempo promedio (minutos)
        $tiempoPromedio = BitacoraActividad::whereNotNull('tiempo_empleado_minutos')
            ->avg('tiempo_empleado_minutos');
        $tiempoPromedio = $tiempoPromedio ? round($tiempoPromedio) : 0;

        // 🔹 Distribución por prioridad
        $prioridades = BitacoraActividad::select('prioridad', DB::raw('COUNT(*) as total'))
            ->groupBy('prioridad')
            ->pluck('total', 'prioridad');

        // 🔹 Fallas por tipo
        $fallasPorTipo = BitacoraActividad::select('tipo_falla', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_falla')
            ->pluck('total', 'tipo_falla');

        // 🔹 Actividades por día del mes actual
        $porDiaRaw = BitacoraActividad::select(DB::raw('DATE(fecha) as dia'), DB::raw('COUNT(*) as total'))
            ->whereMonth('fecha', $hoy->month)
            ->whereYear('fecha', $hoy->year)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $porDia = $porDiaRaw->pluck('total', 'dia')->mapWithKeys(function ($value, $key) {
            return [Carbon::parse($key)->format('d') => $value];
        });

        // 🔹 Últimas actividades
        $ultimas = BitacoraActividad::with('user')
            ->orderBy('fecha', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // 🔔 ALERTAS DE CONTROL (licencias, dominios, etc.)
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
            'alertas'
        ));
    }
}
