<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\TipoEmpresa;
use Illuminate\Http\Request;

class TipoEmpresaController extends Controller
{
    public function index()
    {
        $tipos = TipoEmpresa::orderBy('nombre')->get();

        return view('cobranza_socios.tipos_empresa.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        TipoEmpresa::create([
            'nombre' => $request->nombre,
            'activo' => true,
        ]);

        return back()->with('success', 'Tipo de empresa creado correctamente');
    }

    public function update(Request $request, TipoEmpresa $tipos_empresa)
    {
        $tipos_empresa->update([
            'activo' => $request->activo,
        ]);

        return back()->with('success', 'Estado actualizado');
    }
}
