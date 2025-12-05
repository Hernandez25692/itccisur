<?php

namespace App\Http\Controllers;

use App\Models\BitacoraActividad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardTIController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // Actividades registradas hoy
        $actividadesHoy = BitacoraActividad::whereDate('fecha', $hoy)->count();

        // Pendientes
        $pendientes = BitacoraActividad::where('estado', 'pendiente')->count();

        // Resueltas este mes
        $resueltasMes = BitacoraActividad::where('estado', 'resuelto')
            ->whereMonth('fecha', now()->month)
            ->count();

        // Tiempo promedio
        $tiempoPromedio = BitacoraActividad::whereNotNull('tiempo_empleado_minutos')->avg('tiempo_empleado_minutos');
        $tiempoPromedio = $tiempoPromedio ? round($tiempoPromedio) : 0;

        // Prioridades para gráfica doughnut
        $prioridades = BitacoraActividad::selectRaw('prioridad, COUNT(*) as total')
            ->groupBy('prioridad')
            ->pluck('total', 'prioridad');

        // Tipos de falla
        $fallasPorTipo = BitacoraActividad::selectRaw('tipo_falla, COUNT(*) as total')
            ->groupBy('tipo_falla')
            ->whereNotNull('tipo_falla')
            ->pluck('total', 'tipo_falla');

        // Actividades por día del mes
        $porDia = BitacoraActividad::selectRaw('DATE(fecha) as fecha, COUNT(*) as total')
            ->whereMonth('fecha', now()->month)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->pluck('total', 'fecha');

        // Últimas actividades registradas
        $ultimas = BitacoraActividad::with('user')->latest()->limit(6)->get();

        return view('dashboard-ti.index', compact(
            'actividadesHoy',
            'pendientes',
            'resueltasMes',
            'tiempoPromedio',
            'prioridades',
            'fallasPorTipo',
            'porDia',
            'ultimas'
        ));
    }
}
