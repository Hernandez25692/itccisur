<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('cobranza_socios.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150'
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => true
        ]);

        return back()->with('success', 'Categoría creada correctamente');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $categoria->update([
            'activo' => $request->activo
        ]);

        return back()->with('success', 'Estado actualizado');
    }
}
