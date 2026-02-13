<?php

namespace App\Http\Controllers;

use App\Models\CalendarioEditorial;
use Illuminate\Http\Request;

class CalendarioEditorialDashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes  = $request->input('mes');
        $anio = $request->input('anio');

        $base = CalendarioEditorial::query();

        // 👉 SOLO filtrar si mes y año vienen definidos
        if ($mes && $anio) {
            $base->whereMonth('fecha_publicacion', $mes)
                ->whereYear('fecha_publicacion', $anio);
        }

        /* =========================
         | KPIs
         ========================= */

        $excluirEstados = ['reprogramado', 'reprogramada', 'cancelado', 'cancelada'];

        // ✅ KPIs SOLO con los que “cuentan” (sin reprogramados/cancelados)
        $baseKpi = (clone $base)->whereNotIn('estado', $excluirEstados);

        $totalMes = (clone $baseKpi)->count();

        $porEstado = (clone $baseKpi)
            ->select('estado')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $publicadas = $porEstado->get('publicado', 0);

        $cumplimiento = $totalMes > 0
            ? round(($publicadas / $totalMes) * 100, 1)
            : 0;


        /* =========================
         | GRÁFICAS
         ========================= */

        $porSemana = (clone $base)
            ->select('semana')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('semana')
            ->orderBy('semana')
            ->get();

        $porDia = (clone $base)
            ->select('dia')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('dia')
            ->get();

        $porRed = (clone $base)->get()
            ->pluck('publicar_en')
            ->filter()
            ->flatten()
            ->countBy()
            ->sortDesc();

        /* =========================
         | TABLA: PUBLICACIONES POR DÍA DE LA SEMANA
         ========================= */

        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        $tablaPorDiaSemana = [];
        $semanas = (clone $base)->select('semana')->distinct()->orderBy('semana')->pluck('semana');

        foreach ($semanas as $semana) {
            $fila = ['semana' => 'S ' . $semana];
            foreach ($diasSemana as $dia) {
                $fila[$dia] = (clone $base)
                    ->where('semana', $semana)
                    ->where('dia', $dia)
                    ->count();
            }
            $tablaPorDiaSemana[] = $fila;
        }

        /* =========================
         | TABLA: CONTENIDO POR SEMANA
         ========================= */

        $tiposContenido = [
            'EN VIVO'  => 'VIV',
            'IMAGEN'   => 'IMG',
            'CARRUSEL' => 'CAR',
            'HISTORIA' => 'HIST',
            'VIDEO'    => 'VID',
        ];

        $tablaContenidoPorSemana = [];

        foreach ($semanas as $semana) {
            $fila = ['semana' => 'S ' . $semana];
            foreach ($tiposContenido as $tipo => $alias) {
                $fila[$alias] = (clone $base)
                    ->where('semana', $semana)
                    ->whereJsonContains('contenido', $tipo)
                    ->count();
            }
            $tablaContenidoPorSemana[] = $fila;
        }

        return view('calendario_editorial.dashboard', compact(
            'mes',
            'anio',
            'totalMes',
            'porEstado',
            'porSemana',
            'porDia',
            'porRed',
            'cumplimiento',
            'tablaPorDiaSemana',
            'tablaContenidoPorSemana'
        ));
    }
}
