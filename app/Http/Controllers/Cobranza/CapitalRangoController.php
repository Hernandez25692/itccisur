<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\CapitalRango;
use Illuminate\Http\Request;

class CapitalRangoController extends Controller
{
    public function index()
    {
        $rangos = CapitalRango::orderBy('capital_min')->get();

        return view('cobranza_socios.capital_rangos.index', compact('rangos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'capital_min' => 'required|numeric|min:0',
            'capital_max' => 'required|numeric|gt:capital_min',
            'cuota_mensual' => 'required|numeric|min:0',
            'inscripcion' => 'nullable|numeric|min:0',
        ]);

        CapitalRango::create([
            'capital_min' => $request->capital_min,
            'capital_max' => $request->capital_max,
            'cuota_mensual' => $request->cuota_mensual,
            'inscripcion' => $request->inscripcion ?? 0,
            'activo' => true,
        ]);

        return back()->with('success', 'Rango de capital creado correctamente');
    }

    public function update(Request $request, CapitalRango $capital_rango)
    {
        $capital_rango->update([
            'activo' => $request->activo,
        ]);

        return back()->with('success', 'Estado del rango actualizado');
    }
}
