<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RrhhEmpleado;

class RrhhEmpleadoController extends Controller
{

    public function index()
    {
        $empleados = RrhhEmpleado::orderBy('nombre_completo')->paginate(15);

        return view('rrhh.empleados.index', compact('empleados'));
    }


    public function create()
    {
        return view('rrhh.empleados.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'identidad' => 'nullable|string|max:30',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:25',
            'cargo' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'fecha_contratacion' => 'required|date',
            'vacaciones_acumuladas' => 'nullable|numeric|min:0',
        ]);

        RrhhEmpleado::create($request->all());

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado registrado correctamente');
    }


    public function edit($id)
    {
        $empleado = RrhhEmpleado::findOrFail($id);

        return view('rrhh.empleados.edit', compact('empleado'));
    }


    public function update(Request $request, $id)
    {
        $empleado = RrhhEmpleado::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:25',
            'cargo' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'fecha_contratacion' => 'required|date',
            'vacaciones_acumuladas' => 'nullable|numeric|min:0',
        ]);

        $empleado->update($request->all());

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado actualizado');
    }


    public function destroy($id)
    {
        $empleado = RrhhEmpleado::findOrFail($id);

        $empleado->estado = $empleado->estado === 'activo' ? 'inactivo' : 'activo';

        $empleado->save();

        return redirect()
            ->back()
            ->with('success', 'Estado del empleado actualizado');
    }
}
