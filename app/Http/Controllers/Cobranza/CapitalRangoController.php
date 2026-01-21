<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\CapitalRango;
use App\Models\Cobranza\TipoEmpresa;
use Illuminate\Http\Request;

class CapitalRangoController extends Controller
{
    /**
     * Listado de rangos de capital
     */
    public function index()
    {
        $rangos = CapitalRango::with('tipoEmpresa')
            ->orderBy('tipo_empresa_id')
            ->orderBy('capital_min')
            ->get();

        $tiposEmpresa = TipoEmpresa::where('activo', true)->get();

        return view(
            'cobranza_socios.capital_rangos.index',
            compact('rangos', 'tiposEmpresa')
        );
    }

    /**
     * Crear nuevo rango de capital
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_empresa_id' => 'required|exists:cs_tipos_empresa,id',
            'capital_min'     => 'required|numeric|min:0',
            'capital_max'     => 'required|numeric|gt:capital_min',
            'cuota_mensual'   => 'required|numeric|min:0',
            'inscripcion'     => 'nullable|numeric|min:0',
        ]);

        CapitalRango::create([
            'tipo_empresa_id' => $request->tipo_empresa_id,
            'capital_min'     => $request->capital_min,
            'capital_max'     => $request->capital_max,
            'cuota_mensual'   => $request->cuota_mensual,
            'inscripcion'     => $request->inscripcion ?? 0,
            'activo'          => true,
        ]);

        return back()->with('success', 'Rango de capital creado correctamente');
    }

    /**
     * Activar / desactivar rango
     */
    public function update(Request $request, CapitalRango $capital_rango)
    {
        $capital_rango->update([
            'activo' => (bool) $request->activo,
        ]);

        return back()->with('success', 'Estado del rango actualizado');
    }
}
