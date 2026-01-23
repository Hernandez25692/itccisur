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
            'metodo' => 'required|in:efectivo,transferencia,deposito,cheque,otro',
            'referencia' => 'nullable|string|max:255',
            'comentario' => 'nullable|string',
            'cargos' => 'required|array|min:1',
            'cargos.*' => 'exists:cs_cargos,id',
        ]);

        $empresa = Empresa::with('corte')->findOrFail($data['empresa_id']);

        return DB::transaction(function () use ($data, $empresa, $calc) {

            // 🔒 Tomar y bloquear cargos seleccionados
            $cargos = Cargo::whereIn('id', $data['cargos'])
                ->where('empresa_id', $empresa->id)
                ->where('estado', 'pendiente')
                ->orderBy('fecha_vencimiento', 'asc')
                ->lockForUpdate()
                ->get();

            if ($cargos->isEmpty()) {
                return back()->withErrors('No hay cargos válidos para aplicar el pago.');
            }

            // ✅ Monto REAL del pago (suma exacta de cargos)
            $montoTotal = (float) $cargos->sum('total');

            // 🔹 Crear pago
            $pago = Pago::create([
                'empresa_id' => $empresa->id,
                'fecha_pago' => $data['fecha_pago'],
                'monto' => $montoTotal,
                'metodo' => $data['metodo'],
                'referencia' => $data['referencia'] ?? null,
                'comentario' => $data['comentario'] ?? null,

                // gestor REAL
                'gestor_id' => Auth::id(),
                'created_by' => Auth::id(),
            ]);

            // 🔹 Aplicar pago a cargos (FIFO garantizado)
            foreach ($cargos as $cargo) {

                $pago->cargos()->attach($cargo->id, [
                    'monto_aplicado' => $cargo->total,
                ]);

                $cargo->estado = 'pagado';
                $cargo->pagado_en = now();
                $cargo->save();
            }

            // 🔹 Recalcular empresa (estatus, próxima fecha, etc.)
            $calc->recalcularEmpresa($empresa);

            return redirect()
                ->route('cobranza.empresas.show', $empresa)
                ->with('success', 'Pago registrado correctamente.');
        });
    }
}
