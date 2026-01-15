<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\Empresa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $limite7 = $hoy->copy()->addDays(7);

        // ✅ Empresas en mora (ya viene de recalcularEmpresa sin cargos)
        $enMora = Empresa::where('estatus_cobranza', 'en_mora')->count();

        // ✅ Incobrables (>=18 meses) según tu regla actual
        $incobrables = Empresa::where('meses_mora', '>=', 18)->count();

        // ✅ Por vencer (próxima fecha cobro en los próximos 7 días, y aún no vencida)
        $porVencer7 = Empresa::whereNotNull('proxima_fecha_cobro')
            ->whereDate('proxima_fecha_cobro', '>=', $hoy)
            ->whereDate('proxima_fecha_cobro', '<=', $limite7)
            ->count();

        // ✅ Monto pendiente estimado: suma de la mora calculada por fechas (NO cargos)
        $montoPendiente = (float) Empresa::where('estatus_cobranza', 'en_mora')
            ->sum('valor_mora');

        // ✅ Top 10 por mayor mora (NO cargos)
        $topMora = Empresa::orderByDesc('valor_mora')
            ->limit(10)
            ->get(['id', 'nombre_empresa', 'rtn_empresa', 'valor_mora', 'meses_mora']);

        return view('cobranza_socios.dashboard', compact(
            'enMora',
            'incobrables',
            'porVencer7',
            'montoPendiente',
            'topMora'
        ));
    }
}
