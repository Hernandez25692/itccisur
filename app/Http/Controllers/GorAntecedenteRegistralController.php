<?php

namespace App\Http\Controllers;

use App\Models\GorAntecedenteRegistral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GorAntecedenteRegistralController extends Controller
{
    /**
     * Listado
     */
    public function index(Request $request)
    {
        $query = GorAntecedenteRegistral::with('creador')
            ->orderBy('created_at', 'desc');

        if ($request->filled('circunscripcion')) {
            $query->where('circunscripcion', $request->circunscripcion);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_hasta);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('solicitante_nombre', 'like', '%' . $request->buscar . '%')
                    ->orWhere('numero_exequatur', 'like', '%' . $request->buscar . '%');
            });
        }

        $registros = $query->paginate(10)->withQueryString();

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

            // 📸 comprobante
            'comprobante' => 'nullable|mimes:jpg,jpeg,png,heic,heif|max:10240',

        ]);

        $path = null;

        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');

            $extension = $file->extension() ?: 'jpg';

            $filename = 'gor_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $extension;


            $path = $file->storeAs(
                'gor/comprobantes',
                $filename,
                'public'
            );
        }

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
            'comprobante_path' => $path,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('gor.antecedentes.index')
            ->with('success', 'Registro guardado correctamente.');
    }

    /**
     * Vista detalle
     */
    public function show($id)
    {
        $registro = GorAntecedenteRegistral::with(['creador', 'editor'])
            ->findOrFail($id);

        return view('gor.antecedentes.show', compact('registro'));
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

            // 📸 comprobante
            'comprobante' => 'nullable|mimes:jpg,jpeg,png,heic,heif|max:10240',

        ]);

        $registro = GorAntecedenteRegistral::findOrFail($id);

        $path = $registro->comprobante_path;

        if ($request->hasFile('comprobante')) {

            // eliminar anterior si existe
            if ($registro->comprobante_path) {
                Storage::disk('public')->delete($registro->comprobante_path);
            }

            $file = $request->file('comprobante');

            $extension = $file->extension() ?: 'jpg';

            $filename = 'gor_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $extension;


            $path = $file->storeAs(
                'gor/comprobantes',
                $filename,
                'public'
            );
        }

        $registro->update([
            'circunscripcion' => $request->circunscripcion,
            'fecha_recepcion' => $request->fecha_recepcion,
            'solicitante_nombre' => $request->solicitante_nombre,
            'solicitante_direccion' => $request->solicitante_direccion,
            'numero_exequatur' => $request->numero_exequatur,
            'asiento_tomo_matricula' => $request->asiento_tomo_matricula,
            'tipo_libro' => $request->tipo_libro,
            'motivo' => $request->motivo,
            'comprobante_path' => $path,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('gor.antecedentes.show', $registro->id)
            ->with('success', 'Registro actualizado correctamente.');
    }
}
