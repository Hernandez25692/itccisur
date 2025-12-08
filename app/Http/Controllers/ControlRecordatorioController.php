<?php

namespace App\Http\Controllers;

use App\Models\ControlRecordatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlRecordatorioController extends Controller
{
    /**
     * LISTA GENERAL – Vista principal del módulo.
     */
    public function index()
    {
        $recordatorios = ControlRecordatorio::orderBy('fecha_vencimiento', 'ASC')->get();

        // Para alertas de 15 días
        $alertas = ControlRecordatorio::where('notificar', true)
            ->where('atendido', false)
            ->whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(15))
            ->orderBy('fecha_vencimiento', 'ASC')
            ->get();

        return view('control.index', compact('recordatorios', 'alertas'));
    }


    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create()
    {
        return view('control.create');
    }

    /**
     * GUARDAR REGISTRO
     */
    public function store(Request $request)
    {
        $request->validate([
            'actividad' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_ejecucion' => 'nullable|date',
            'fecha_vencimiento' => 'required|date',
            'dias_recordatorio' => 'required|integer|min:1|max:90',
        ]);

        ControlRecordatorio::create([
            'user_id' => Auth::id(),
            'actividad' => $request->actividad,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha_ejecucion' => $request->fecha_ejecucion,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'dias_recordatorio' => $request->dias_recordatorio,
            'notificar' => true,
            'atendido' => false,
        ]);

        return redirect()
            ->route('control.index')
            ->with('success', 'Registro creado correctamente.');
    }

    /**
     * FORMULARIO DE EDICIÓN
     */
    public function edit($id)
    {
        $recordatorio = ControlRecordatorio::findOrFail($id);
        return view('control.edit', compact('recordatorio'));
    }

    /**
     * ACTUALIZAR REGISTRO
     */
    public function update(Request $request, $id)
    {
        $recordatorio = ControlRecordatorio::findOrFail($id);

        $request->validate([
            'actividad' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_ejecucion' => 'nullable|date',
            'fecha_vencimiento' => 'required|date',
            'dias_recordatorio' => 'required|integer|min:1|max:90',
            'notificar' => 'nullable|boolean',
            'atendido' => 'nullable|boolean',
        ]);

        $recordatorio->update([
            'actividad' => $request->actividad,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha_ejecucion' => $request->fecha_ejecucion,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'dias_recordatorio' => $request->dias_recordatorio,
            'notificar' => $request->has('notificar'),
            'atendido' => $request->has('atendido'),
        ]);

        return redirect()
            ->route('control.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * ELIMINAR REGISTRO
     */
    public function destroy($id)
    {
        ControlRecordatorio::findOrFail($id)->delete();

        return redirect()
            ->route('control.index')
            ->with('success', 'Registro eliminado correctamente.');
    }
}
