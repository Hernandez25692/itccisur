<?php

namespace App\Http\Controllers;

use App\Models\BitacoraActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BitacoraActividadController extends Controller
{
    /**
     * Mostrar todas las actividades (vista del gerente).
     */
    public function index(Request $request)
    {
        $query = BitacoraActividad::with('user')->orderBy('fecha', 'DESC')->orderBy('hora_inicio', 'DESC');

        // Filtros rápidos
        if ($request->estado && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        if ($request->prioridad && $request->prioridad !== 'todas') {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->desde) {
            $query->whereDate('fecha', '>=', $request->desde);
        }

        if ($request->hasta) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        $actividades = $query->paginate(15);

        return view('bitacora.index', compact('actividades'));
    }

    /**
     * Formulario de creación rápida.
     */
    public function create()
    {
        return view('bitacora.create');
    }

    /**
     * Guardar registro.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'equipo_afectado' => 'nullable|string|max:255',
            'tipo_falla' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'nullable',
            'hora_fin' => 'nullable',
            'solucion_aplicada' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'prioridad' => 'required|string|in:baja,media,alta,critica',

            // 🔥 ESTADOS CORRECTOS
            'estado' => 'required|string|in:pendiente,en_proceso,resuelto',

            'evidencia' => 'nullable|image|max:8192',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Evidencia
        if ($request->hasFile('evidencia')) {
            $data['evidencia'] = $request->file('evidencia')->store('bitacora_evidencias', 'public');
        }

        // Marcar solucionado
        if ($request->estado === 'resuelto') {
            $data['solucionado'] = true;

            if ($request->hora_inicio && $request->hora_fin) {
                $data['tiempo_empleado_minutos'] =
                    (strtotime($request->hora_fin) - strtotime($request->hora_inicio)) / 60;
            }
        } else {
            $data['solucionado'] = false;
        }

        BitacoraActividad::create($data);

        return redirect()->route('bitacora.index')->with('success', 'Actividad registrada correctamente.');
    }


    /**
     * Editar registro.
     */
    public function edit($id)
    {
        $actividad = BitacoraActividad::findOrFail($id);
        return view('bitacora.edit', compact('actividad'));
    }

    /**
     * Actualizar.
     */
    public function update(Request $request, $id)
    {
        $actividad = BitacoraActividad::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string|in:pendiente,en_proceso,resuelto',
            'prioridad' => 'required|string|in:baja,media,alta,critica',
            'evidencia' => 'nullable|image|max:4096',
        ]);


        $data = $request->all();

        // Manejo de nueva foto
        if ($request->hasFile('evidencia')) {
            // Borrar la evidencia anterior si existe
            if ($actividad->evidencia) {
                Storage::disk('public')->delete($actividad->evidencia);
            }
            $data['evidencia'] = $request->file('evidencia')->store('bitacora_evidencias', 'public');
        }

        // Si se marcó como resuelto
        if ($request->estado === 'resuelto') {
            $data['solucionado'] = true;
        } else {
            $data['solucionado'] = false;
        }

        $actividad->update($data);

        return redirect()->route('bitacora.index')->with('success', 'Actividad actualizada correctamente.');
    }

    public function show($id)
    {
        $actividad = BitacoraActividad::with('user')->findOrFail($id);
        return view('bitacora.show', compact('actividad'));
    }
}
