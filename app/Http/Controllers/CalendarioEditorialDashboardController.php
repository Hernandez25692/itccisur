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
        $filtros = $request->only(['area', 'red', 'tipo']);

        $base = CalendarioEditorial::query()->filtrar($filtros);

        if ($mes && $anio) {
            $base->whereMonth('fecha_publicacion', $mes)
                ->whereYear('fecha_publicacion', $anio);
        } elseif ($anio) {
            $base->whereYear('fecha_publicacion', $anio);
        }

        // Una sola consulta: el volumen mensual es chico y se agrupa en memoria
        $registros = $base->get(['id', 'semana', 'dia', 'estado', 'area', 'contenido', 'publicar_en', 'fecha_publicacion']);

        // Igual que antes: lo reprogramado o cancelado no cuenta para el cumplimiento
        $fueraDeMeta = [...CalendarioEditorial::ESTADOS_FUERA_DE_META, 'reprogramada', 'cancelada'];
        $enMeta = $registros->reject(fn ($r) => in_array($r->estado, $fueraDeMeta, true));

        $totalMes = $enMeta->count();
        $porEstado = $enMeta->countBy('estado');
        $publicadas = $porEstado->get('publicado', 0);
        $cumplimiento = $totalMes > 0 ? round(($publicadas / $totalMes) * 100, 1) : 0;

        $reprogramadas = $registros->where('estado', 'reprogramado')->count();
        $canceladas = $registros->where('estado', 'cancelado')->count();
        $vencidas = $registros->filter(fn ($r) => $r->estado === 'pendiente' && $r->fecha_publicacion->isBefore(today()))->count();

        $porSemana = $registros->groupBy('semana')
            ->map(fn ($grupo, $semana) => (object) ['semana' => $semana, 'total' => $grupo->count()])
            ->sortBy('semana')
            ->values();

        $porDia = collect(CalendarioEditorial::DIAS)
            ->map(fn ($dia) => (object) ['dia' => $dia, 'total' => $registros->where('dia', $dia)->count()])
            ->filter(fn ($d) => $d->total > 0)
            ->values();

        $porRed = $registros->pluck('publicar_en')->filter()->flatten()->countBy()->sortDesc();

        $porArea = $registros->pluck('area')->filter()->countBy()->sortDesc()->take(8);

        $tiposContenido = [
            'EN VIVO'  => 'VIV',
            'IMAGEN'   => 'IMG',
            'CARRUSEL' => 'CAR',
            'HISTORIA' => 'HIST',
            'VIDEO'    => 'VID',
        ];

        $tablaPorDiaSemana = [];
        $tablaContenidoPorSemana = [];

        foreach ($registros->groupBy('semana')->sortKeys() as $semana => $grupo) {
            $fila = ['semana' => 'S ' . $semana];
            foreach (CalendarioEditorial::DIAS as $dia) {
                $fila[$dia] = $grupo->where('dia', $dia)->count();
            }
            $tablaPorDiaSemana[] = $fila;

            $fila = ['semana' => 'S ' . $semana];
            foreach ($tiposContenido as $tipo => $alias) {
                $fila[$alias] = $grupo->filter(fn ($r) => in_array($tipo, $r->contenido ?? [], true))->count();
            }
            $tablaContenidoPorSemana[] = $fila;
        }

        return view('calendario_editorial.dashboard', compact(
            'mes',
            'anio',
            'filtros',
            'totalMes',
            'porEstado',
            'porSemana',
            'porDia',
            'porRed',
            'porArea',
            'cumplimiento',
            'reprogramadas',
            'canceladas',
            'vencidas',
            'tablaPorDiaSemana',
            'tablaContenidoPorSemana'
        ));
    }
}
