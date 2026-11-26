<?php

namespace App\Http\Controllers;

use App\Models\BitacoraActividad;
use App\Models\CalendarioEditorial;
use App\Models\ControlRecordatorio;
use App\Models\PlanTrabajo;
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

        // El rango de días se filtra en PHP para no depender de DATEDIFF (solo MySQL)
        $alertas = ControlRecordatorio::where('notificar', true)
            ->where('atendido', false)
            ->whereDate('fecha_vencimiento', '>=', $hoy)
            ->orderBy('fecha_vencimiento', 'ASC')
            ->get()
            ->filter(fn ($alerta) => $hoy->diffInDays(Carbon::parse($alerta->fecha_vencimiento)) <= $alerta->dias_recordatorio)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Integración: Plan de Trabajo y Calendario Editorial
        |--------------------------------------------------------------------------
        | No dependen de los filtros de la bitácora.
        */

        $bitacorasConPlan = (clone $queryBase)->whereNotNull('plan_actividad_id')->count();
        $plan = $this->resumenPlan();
        $planesEnRevision = PlanTrabajo::where('estado', 'enviado')->orderBy('anio')->get(['id', 'anio']);
        $calendario = $this->resumenCalendario($hoy);

        return view('dashboard-ti.index', compact(
            'bitacorasConPlan',
            'plan',
            'planesEnRevision',
            'calendario',
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

    /** Avance del plan aprobado vigente y sus actividades atrasadas. */
    private function resumenPlan(): ?array
    {
        $plan = PlanTrabajo::vigente();

        if (!$plan) {
            return null;
        }

        $plan->load(['actividades' => fn ($q) => $q->withSum('ejecuciones', 'avance')->withCount('bitacoras')]);
        $actividades = $plan->actividades;
        $atrasadas = $actividades->filter->atrasada->sortBy('mes_previsto_numero')->values();

        return [
            'modelo' => $plan,
            'avance' => $plan->avance_global,
            'total' => $actividades->count(),
            'finalizadas' => $actividades->where('progreso', '>=', 100)->count(),
            'en_progreso' => $actividades->filter(fn ($a) => $a->progreso > 0 && $a->progreso < 100)->count(),
            'atrasadas' => $atrasadas->count(),
            'lista_atrasadas' => $atrasadas->take(6),
            'del_mes' => $actividades->filter(fn ($a) => $a->mes_previsto_numero === now()->month && $a->progreso < 100)->count(),
        ];
    }

    /** Cumplimiento del calendario editorial en el mes actual. */
    private function resumenCalendario(Carbon $hoy): array
    {
        $delMes = CalendarioEditorial::whereYear('fecha_publicacion', $hoy->year)
            ->whereMonth('fecha_publicacion', $hoy->month)
            ->get(['id', 'estado', 'fecha_publicacion']);

        $enMeta = $delMes->reject(fn ($r) => in_array($r->estado, CalendarioEditorial::ESTADOS_FUERA_DE_META, true));
        $publicadas = $enMeta->where('estado', 'publicado')->count();

        return [
            'total' => $enMeta->count(),
            'publicadas' => $publicadas,
            'cumplimiento' => $enMeta->count() ? round($publicadas / $enMeta->count() * 100) : 0,
            'vencidas' => CalendarioEditorial::vencidas()->count(),
            'proximas' => CalendarioEditorial::where('estado', 'pendiente')
                ->whereBetween('fecha_publicacion', [$hoy->toDateString(), $hoy->copy()->addDays(7)->toDateString()])
                ->orderBy('fecha_publicacion')
                ->orderBy('hora')
                ->limit(5)
                ->get(['id', 'tema', 'fecha_publicacion', 'hora', 'publicar_en']),
        ];
    }
}
