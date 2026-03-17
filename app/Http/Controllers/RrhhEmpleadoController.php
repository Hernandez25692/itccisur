<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RrhhEmpleado;

class RrhhEmpleadoController extends Controller
{

    public function index(Request $request)
    {
        $query = RrhhEmpleado::query();

        // 🔍 BUSQUEDA (nombre o identidad)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->search . '%')
                    ->orWhere('identidad', 'like', '%' . $request->search . '%');
            });
        }

        // 📌 ESTADO
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 📌 AREA
        if ($request->filled('area')) {
            $query->where('area', 'like', '%' . $request->area . '%');
        }

        // 🔥 CARGAR RELACION (para no hacer mil consultas)
        $query->with('periodosVacaciones');

        $empleados = $query->orderBy('nombre_completo')->paginate(15);

        // ⚠️ FILTRO POR PENDIENTES (se hace en colección porque es cálculo dinámico)
        if ($request->filled('pendiente')) {

            $empleados->getCollection()->transform(function ($empleado) use ($request) {

                $anio = date('Y');

                $periodo = $empleado->periodosVacaciones->where('anio', $anio)->first();

                $vacaciones = $periodo
                    ? $periodo->dias_correspondientes
                    : $empleado->vacacionesPorLey();

                $acumulado = $periodo
                    ? $periodo->acumulado_anterior
                    : $empleado->vacaciones_acumuladas ?? 0;

                $tomado = $periodo ? $periodo->total_tomado : 0;

                $pendiente = $vacaciones + $acumulado - $tomado;

                $empleado->pendiente_calculado = $pendiente;

                return $empleado;
            });

            $empleados = $empleados->setCollection(
                $empleados->getCollection()->filter(function ($empleado) use ($request) {

                    $pendiente = $empleado->pendiente_calculado;

                    if ($request->pendiente == 'con') {
                        return $pendiente > 0;
                    }

                    if ($request->pendiente == 'sin') {
                        return $pendiente <= 0;
                    }

                    if ($request->pendiente == 'critico') {
                        return $pendiente > 15;
                    }

                    return true;
                })
            );
        }

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
