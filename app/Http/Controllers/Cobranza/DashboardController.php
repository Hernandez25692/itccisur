<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\Empresa;
use App\Models\Cobranza\Cargo;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $limite7 = $hoy->copy()->addDays(7);

        /**
         * ===============================
         * EMPRESAS EN MORA
         * ===============================
         * Al menos un cargo pendiente
         */
        $enMora = Empresa::whereHas(
            'cargos',
            fn($q) =>
            $q->where('estado', 'pendiente')
        )->count();

        /**
         * ===============================
         * INCOBRABLES (>= 18 cargos pendientes)
         * ===============================
         */
        $incobrables = Empresa::withCount([
            'cargos as pendientes_count' => fn($q) =>
            $q->where('estado', 'pendiente')
        ])
            ->having('pendientes_count', '>=', 18)
            ->count();

        /**
         * ===============================
         * POR VENCER EN 7 DÍAS
         * ===============================
         * Empresas con cargos que vencen pronto
         */
        $porVencer7 = Cargo::where('estado', 'pendiente')
            ->whereBetween('fecha_vencimiento', [$hoy, $limite7])
            ->distinct('empresa_id')
            ->count('empresa_id');

        /**
         * ===============================
         * MONTO PENDIENTE REAL
         * ===============================
         */
        $montoPendiente = (float) Cargo::where('estado', 'pendiente')
            ->sum('total');

        /**
         * ===============================
         * TOP 10 EMPRESAS CON MAYOR MORA REAL
         * ===============================
         */
        $topMora = Empresa::withSum([
            'cargos as mora_real' => fn($q) =>
            $q->where('estado', 'pendiente')
        ], 'total')
            ->withCount([
                'cargos as pendientes_count' => fn($q) =>
                $q->where('estado', 'pendiente')
            ])
            ->having('mora_real', '>', 0)
            ->orderByDesc('mora_real')
            ->limit(10)
            ->get([
                'id',
                'nombre_empresa',
                'rtn_empresa'
            ]);

        return view('cobranza_socios.dashboard', compact(
            'enMora',
            'incobrables',
            'porVencer7',
            'montoPendiente',
            'topMora'
        ));
    }
}
