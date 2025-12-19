<?php

namespace App\Http\Controllers;

use App\Models\GorAntecedenteRegistral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GorAntecedenteRegistralController extends Controller
{
    /**
     * Listado (para uso futuro)
     */
    public function index()
    {
        $registros = GorAntecedenteRegistral::orderBy('created_at', 'desc')->paginate(15);

        return view('gor.antecedentes.index', compact('registros'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('gor.antecedentes.create');
    }

    /**
     * Guardar nuevo registro
     */
    public function store(Request $request)
    {
        $request->validate([
            'circunscripcion' => 'required|string',
            'fecha_recepcion' => 'required|date',
            'solicitante_nombre' => 'required|string|max:255',
            'solicitante_direccion' => 'nullable|string',
            'numero_exequatur' => 'nullable|string',
            'asiento_tomo_matricula' => 'nullable|string',
            'tipo_libro' => 'nullable|string',
            'motivo' => 'nullable|string',
        ]);

        GorAntecedenteRegistral::create([
            'centro' => 'CAS',
            'circunscripcion' => $request->circunscripcion,
            'fecha_recepcion' => $request->fecha_recepcion,
            'solicitante_nombre' => $request->solicitante_nombre,
            'solicitante_direccion' => $request->solicitante_direccion,
            'numero_exequatur' => $request->numero_exequatur,
            'asiento_tomo_matricula' => $request->asiento_tomo_matricula,
            'tipo_libro' => $request->tipo_libro,
            'motivo' => $request->motivo,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Registro guardado correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $registro = GorAntecedenteRegistral::findOrFail($id);

        return view('gor.antecedentes.edit', compact('registro'));
    }

    /**
     * Actualizar registro
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'circunscripcion' => 'required|string',
            'fecha_recepcion' => 'required|date',
            'solicitante_nombre' => 'required|string|max:255',
            'solicitante_direccion' => 'nullable|string',
            'numero_exequatur' => 'nullable|string',
            'asiento_tomo_matricula' => 'nullable|string',
            'tipo_libro' => 'nullable|string',
            'motivo' => 'nullable|string',
        ]);

        $registro = GorAntecedenteRegistral::findOrFail($id);

        $registro->update([
            'circunscripcion' => $request->circunscripcion,
            'fecha_recepcion' => $request->fecha_recepcion,
            'solicitante_nombre' => $request->solicitante_nombre,
            'solicitante_direccion' => $request->solicitante_direccion,
            'numero_exequatur' => $request->numero_exequatur,
            'asiento_tomo_matricula' => $request->asiento_tomo_matricula,
            'tipo_libro' => $request->tipo_libro,
            'motivo' => $request->motivo,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Registro actualizado correctamente.');
    }
}
