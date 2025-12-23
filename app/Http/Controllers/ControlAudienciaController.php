<?php

namespace App\Http\Controllers;

use App\Models\ControlAudiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlAudienciaController extends Controller
{
    /**
     * Listado de audiencias
     */
    public function index(Request $request)
    {
        $query = ControlAudiencia::with('creador')
            ->orderBy('created_at', 'desc');

        // 🔎 Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre_solicitante', 'like', '%' . $request->nombre . '%');
        }

        // 🔎 Filtro por número de documento
        if ($request->filled('documento')) {
            $query->where('numero_documento', 'like', '%' . $request->documento . '%');
        }

        // 📅 Filtro por fecha de recepción (desde)
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_desde);
        }

        // 📅 Filtro por fecha de recepción (hasta)
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_hasta);
        }

        // 🟢 Estado
        if ($request->filled('estado')) {
            if ($request->estado === 'atendido') {
                $query->whereNotNull('fecha_hora_atencion');
            }
            if ($request->estado === 'pendiente') {
                $query->whereNull('fecha_hora_atencion');
            }
        }

        // 📊 KPIs (clonamos el query para no afectar la paginación)
        $total = (clone $query)->count();
        $atendidos = (clone $query)->whereNotNull('fecha_hora_atencion')->count();
        $pendientes = (clone $query)->whereNull('fecha_hora_atencion')->count();

        $audiencias = $query->paginate(10)->withQueryString();

        return view('audiencias.index', compact(
            'audiencias',
            'total',
            'atendidos',
            'pendientes'
        ));
    }



    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('audiencias.create');
    }

    /**
     * Guardar nueva audiencia
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_solicitante'   => 'required|string|max:255',
            'fecha_recepcion'      => 'required|date',
            'fecha_hora_atencion'  => 'nullable|date',
            'motivo'               => 'required|string',
            'numero_documento'     => 'required|string|max:255',
            'dictamen'             => 'nullable|string',
        ]);

        ControlAudiencia::create([
            'nombre_solicitante'  => $request->nombre_solicitante,
            'fecha_recepcion'     => $request->fecha_recepcion,
            'fecha_hora_atencion' => $request->fecha_hora_atencion,
            'motivo'              => $request->motivo,
            'numero_documento'    => $request->numero_documento,
            'dictamen'            => $request->dictamen,
            'created_by'          => Auth::id(),
        ]);

        return redirect()
            ->route('audiencias.index')
            ->with('success', 'Audiencia registrada correctamente.');
    }

    /**
     * Vista detalle (expediente)
     */
    public function show($id)
    {
        $audiencia = ControlAudiencia::with(['creador', 'editor'])
            ->findOrFail($id);

        return view('audiencias.show', compact('audiencia'));
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $audiencia = ControlAudiencia::findOrFail($id);

        return view('audiencias.edit', compact('audiencia'));
    }

    /**
     * Actualizar audiencia
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_solicitante'   => 'required|string|max:255',
            'fecha_recepcion'      => 'required|date',
            'fecha_hora_atencion'  => 'nullable|date',
            'motivo'               => 'required|string',
            'numero_documento'     => 'required|string|max:255',
            'dictamen'             => 'nullable|string',
        ]);

        $audiencia = ControlAudiencia::findOrFail($id);

        $audiencia->update([
            'nombre_solicitante'  => $request->nombre_solicitante,
            'fecha_recepcion'     => $request->fecha_recepcion,
            'fecha_hora_atencion' => $request->fecha_hora_atencion,
            'motivo'              => $request->motivo,
            'numero_documento'    => $request->numero_documento,
            'dictamen'            => $request->dictamen,
            'updated_by'          => Auth::id(),
        ]);

        return redirect()
            ->route('audiencias.show', $audiencia->id)
            ->with('success', 'Audiencia actualizada correctamente.');
    }
}
