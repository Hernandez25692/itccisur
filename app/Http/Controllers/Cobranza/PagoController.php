<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\{Empresa, Pago, Cargo};
use App\Services\Cobranza\CobranzaCalculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $q = Pago::query()->with('empresa');

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $q->whereHas('empresa', fn($x) => $x->where('nombre_empresa', 'like', "%$b%")->orWhere('rtn_empresa', 'like', "%$b%"));
        }

        $pagos = $q->orderBy('fecha_pago', 'desc')->paginate(15)->withQueryString();

        return view('cobranza_socios.pagos.index', compact('pagos'));
    }

    public function create(Empresa $empresa)
    {
        $empresa->load([
            'cargos' => fn($q) => $q->where('estado', 'pendiente')->orderBy('fecha_vencimiento', 'asc')
        ]);

        return view('cobranza_socios.pagos.create', compact('empresa'));
    }

    public function store(Request $request, CobranzaCalculoService $calc)
    {
        $data = $request->validate([
            'empresa_id' => 'required|exists:cs_empresas,id',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'metodo' => 'required|in:efectivo,transferencia,deposito,cheque,otro',
            'referencia' => 'nullable|string|max:255',
            'comentario' => 'nullable|string',
            'gestor_id' => 'nullable|exists:users,id',
        ]);

        $empresa = Empresa::with(['corte'])->findOrFail($data['empresa_id']);

        return DB::transaction(function () use ($data, $empresa, $calc) {

            $pago = Pago::create([
                'empresa_id' => $empresa->id,
                'fecha_pago' => $data['fecha_pago'],
                'monto' => $data['monto'],
                'metodo' => $data['metodo'],
                'referencia' => $data['referencia'] ?? null,
                'comentario' => $data['comentario'] ?? null,
                'gestor_id' => $data['gestor_id']
                    ?? (Auth::user()->hasRole('cobranza') ? Auth::id() : null),

                'created_by' => Auth::id(),
            ]);

            // Aplicación FIFO a cargos pendientes
            $restante = (float)$pago->monto;

            $cargos = Cargo::where('empresa_id', $empresa->id)
                ->where('estado', 'pendiente')
                ->orderBy('fecha_vencimiento', 'asc')
                ->lockForUpdate()
                ->get();

            foreach ($cargos as $cargo) {
                if ($restante <= 0) break;

                $saldoCargo = (float)$cargo->total; // si luego guardas pagos parciales, cambias esto
                $aplicar = min($restante, $saldoCargo);

                // ligar pago-cargo
                $pago->cargos()->attach($cargo->id, ['monto_aplicado' => $aplicar]);

                $restante -= $aplicar;

                // Si cubrió el cargo completo, marcar pagado
                if ($aplicar >= $saldoCargo) {
                    $cargo->estado = 'pagado';
                    $cargo->pagado_en = now();
                    $cargo->save();
                }
            }

            // recalcular empresa
            $calc->recalcularEmpresa($empresa);

            return back()->with('success', 'Pago registrado correctamente.');
        });
    }
}
