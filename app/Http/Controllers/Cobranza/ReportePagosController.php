<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\Pago;
use App\Models\Cobranza\Empresa;
use App\Models\User;
use Illuminate\Http\Request;

class ReportePagosController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::query()
            ->with(['empresa', 'usuario'])
            ->orderBy('fecha_pago', 'desc');

        // 🔎 Filtros
        if ($request->filled('desde')) {
            $query->whereDate('fecha_pago', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha_pago', '<=', $request->hasta);
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->filled('usuario_id')) {
            $query->where('created_by', $request->usuario_id);
        }

        if ($request->filled('metodo')) {
            $query->where('metodo', $request->metodo);
        }

        $pagos = $query->paginate(15)->withQueryString();

        // 🔢 Totales
        $totalMonto = $query->clone()->sum('monto');

        return view('cobranza_socios.reportes.pagos_diarios', [
            'pagos' => $pagos,
            'totalMonto' => $totalMonto,
            'empresas' => Empresa::orderBy('nombre_empresa')->get(),
            'usuarios' => User::role('cobranza')->orderBy('name')->get(),
        ]);
    }
}
