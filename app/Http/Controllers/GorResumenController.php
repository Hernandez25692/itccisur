<?php

namespace App\Http\Controllers;

use App\Models\GorAntecedenteRegistral;
use App\Models\ControlAudiencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GorResumenController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // Filtros (reusables)
        // =========================
        $fechaDesde = $request->filled('fecha_desde') ? Carbon::parse($request->fecha_desde)->startOfDay() : null;
        $fechaHasta = $request->filled('fecha_hasta') ? Carbon::parse($request->fecha_hasta)->endOfDay() : null;
        $circunscripcion = $request->filled('circunscripcion') ? $request->circunscripcion : null;

        // =========================
        // Base Queries (Antecedentes)
        // =========================
        $qAnt = GorAntecedenteRegistral::query();
        if ($circunscripcion) $qAnt->where('circunscripcion', $circunscripcion);
        if ($fechaDesde) $qAnt->whereDate('fecha_recepcion', '>=', $fechaDesde->toDateString());
        if ($fechaHasta) $qAnt->whereDate('fecha_recepcion', '<=', $fechaHasta->toDateString());

        // =========================
        // Base Queries (Audiencias)
        // =========================
        $qAud = ControlAudiencia::query();
        if ($fechaDesde) $qAud->whereDate('fecha_recepcion', '>=', $fechaDesde->toDateString());
        if ($fechaHasta) $qAud->whereDate('fecha_recepcion', '<=', $fechaHasta->toDateString());

        // =========================
        // KPIs generales
        // =========================
        $totalAntecedentes = (clone $qAnt)->count();
        $totalAudiencias   = (clone $qAud)->count();

        $audAtendidas = (clone $qAud)->whereNotNull('fecha_hora_atencion')->count();
        $audPendientes = (clone $qAud)->whereNull('fecha_hora_atencion')->count();

        // =========================
        // Tiempo de respuesta (Audiencias)
        // promedio, mediana, p90 (en horas)
        // =========================
        $tiemposHoras = (clone $qAud)
            ->whereNotNull('fecha_hora_atencion')
            ->get(['fecha_recepcion', 'fecha_hora_atencion'])
            ->map(function ($row) {
                $ini = Carbon::parse($row->fecha_recepcion);
                $fin = Carbon::parse($row->fecha_hora_atencion);
                return max(0, $ini->diffInMinutes($fin) / 60);
            })
            ->sort()
            ->values();

        $promedioHoras = $tiemposHoras->count() ? round($tiemposHoras->avg(), 2) : 0;

        $medianaHoras = 0;
        if ($tiemposHoras->count()) {
            $n = $tiemposHoras->count();
            $mid = intdiv($n, 2);
            $medianaHoras = ($n % 2 === 1)
                ? round($tiemposHoras[$mid], 2)
                : round(($tiemposHoras[$mid - 1] + $tiemposHoras[$mid]) / 2, 2);
        }

        $p90Horas = 0;
        if ($tiemposHoras->count()) {
            $idx = (int) floor(0.90 * ($tiemposHoras->count() - 1));
            $p90Horas = round($tiemposHoras[$idx], 2);
        }

        // =========================
        // Pendientes: antigüedad promedio (días)
        // =========================
        $pendientesDiasProm = (clone $qAud)
            ->whereNull('fecha_hora_atencion')
            ->get(['fecha_recepcion'])
            ->map(fn($r) => Carbon::parse($r->fecha_recepcion)->diffInDays(Carbon::now()))
            ->avg();
        $pendientesDiasProm = $pendientesDiasProm ? round($pendientesDiasProm, 1) : 0;

        // =========================
        // Top “Asiento/Tomo/Matrícula” más consultado
        // =========================
        $topAsientos = (clone $qAnt)
            ->whereNotNull('asiento_tomo_matricula')
            ->where('asiento_tomo_matricula', '!=', '')
            ->select('asiento_tomo_matricula', DB::raw('COUNT(*) as total'))
            ->groupBy('asiento_tomo_matricula')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // =========================
        // Tipo de libro más consultado
        // =========================
        $topLibros = (clone $qAnt)
            ->whereNotNull('tipo_libro')
            ->where('tipo_libro', '!=', '')
            ->select('tipo_libro', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_libro')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // =========================
        // Total por Circunscripción
        // =========================
        $porCircunscripcion = (clone $qAnt)
            ->select('circunscripcion', DB::raw('COUNT(*) as total'))
            ->groupBy('circunscripcion')
            ->orderByDesc('total')
            ->get();

        // =========================
        // Quién solicita más (Antecedentes)
        // =========================
        $topSolicitantesAnt = (clone $qAnt)
            ->whereNotNull('solicitante_nombre')
            ->where('solicitante_nombre', '!=', '')
            ->select('solicitante_nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('solicitante_nombre')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // =========================
        // Quién solicita más (Audiencias)
        // =========================
        $topSolicitantesAud = (clone $qAud)
            ->whereNotNull('nombre_solicitante')
            ->where('nombre_solicitante', '!=', '')
            ->select('nombre_solicitante', DB::raw('COUNT(*) as total'))
            ->groupBy('nombre_solicitante')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // =========================
        // Tendencia por mes (últimos 12 meses)
        // =========================
        $inicio12 = Carbon::now()->startOfMonth()->subMonths(11);

        $antPorMes = GorAntecedenteRegistral::query()
            ->when($circunscripcion, fn($q) => $q->where('circunscripcion', $circunscripcion))
            ->whereDate('fecha_recepcion', '>=', $inicio12->toDateString())
            ->selectRaw("DATE_FORMAT(fecha_recepcion, '%Y-%m') as mes, COUNT(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $audPorMes = ControlAudiencia::query()
            ->whereDate('fecha_recepcion', '>=', $inicio12->toDateString())
            ->selectRaw("DATE_FORMAT(fecha_recepcion, '%Y-%m') as mes, COUNT(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Lista para combo de circunscripción (desde BD)
        $circunscripciones = GorAntecedenteRegistral::query()
            ->select('circunscripcion')
            ->whereNotNull('circunscripcion')
            ->where('circunscripcion', '!=', '')
            ->distinct()
            ->orderBy('circunscripcion')
            ->pluck('circunscripcion');

        return view('gor.resumen', compact(
            'fechaDesde',
            'fechaHasta',
            'circunscripcion',
            'circunscripciones',

            'totalAntecedentes',
            'totalAudiencias',
            'audAtendidas',
            'audPendientes',

            'promedioHoras',
            'medianaHoras',
            'p90Horas',
            'pendientesDiasProm',

            'topAsientos',
            'topLibros',
            'porCircunscripcion',
            'topSolicitantesAnt',
            'topSolicitantesAud',
            'antPorMes',
            'audPorMes'
        ));
    }
}
