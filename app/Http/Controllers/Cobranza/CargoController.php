<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\{Empresa, Cargo};
use App\Services\Cobranza\CargosGeneratorService;
use App\Services\Cobranza\CobranzaCalculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargoController extends Controller
{
    public function index(Request $request)
    {
        $q = Cargo::query()->with('empresa');

        if ($request->filled('estado')) $q->where('estado', $request->estado);
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $q->whereHas('empresa', fn($x) => $x->where('nombre_empresa', 'like', "%$b%")->orWhere('rtn_empresa', 'like', "%$b%"));
        }

        $cargos = $q->orderBy('fecha_vencimiento', 'desc')->paginate(15)->withQueryString();

        return view('cobranza_socios.cargos.index', compact('cargos'));
    }

    public function generar(Request $request, CargosGeneratorService $gen, CobranzaCalculoService $calc)
    {
        $data = $request->validate([
            'empresa_id' => 'required|exists:cs_empresas,id',
            'cantidad' => 'required|integer|min:1|max:24',
        ]);

        $empresa = Empresa::with('corte')->findOrFail($data['empresa_id']);

        return DB::transaction(function () use ($empresa, $data, $gen, $calc) {
            for ($i = 0; $i < $data['cantidad']; $i++) {
                $gen->generarCargoSiguiente($empresa);
            }
            $calc->recalcularEmpresa($empresa);

            return redirect()->route('cobranza.empresas.show', $empresa)->with('success', 'Cargos generados correctamente.');
        });
    }

    public function anular(Cargo $cargo, CobranzaCalculoService $calc)
    {
        if ($cargo->estado === 'pagado') {
            return back()->with('error', 'No se puede anular un cargo ya pagado.');
        }

        $cargo->estado = 'anulado';
        $cargo->save();

        $empresa = $cargo->empresa()->with('corte')->first();
        $calc->recalcularEmpresa($empresa);

        return back()->with('success', 'Cargo anulado.');
    }
}
