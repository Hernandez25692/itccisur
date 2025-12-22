<?php

namespace App\Http\Controllers;

use App\Models\CalendarioEditorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CalendarioEditorialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin_ti|calendario']);
    }

    /* =========================
     |  LISTADO
     ========================= */
    public function index(Request $request)
    {
        $query = CalendarioEditorial::query()
            ->with('creador', 'publicadoPor')
            ->orderBy('fecha_publicacion');

        // Filtros
        $query->semana($request->semana)
            ->estado($request->estado)
            ->mes($request->mes)
            ->anio($request->anio);



        $registros = $query
            ->orderBy('fecha_publicacion', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('calendario_editorial.index', compact('registros'));
    }

    /* =========================
     |  FORM CREAR
     ========================= */
    public function create()
    {
        return view('calendario_editorial.create');
    }

    /* =========================
     |  GUARDAR
     ========================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'semana' => 'required|integer',
            'dia' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'hora' => 'nullable',
            'tema' => 'required|string',
            'area' => 'nullable|string',
            'encabezado' => 'nullable|string',
            'contenido' => 'nullable|array',
            'publicar_en' => 'nullable|array',
            'etiquetas' => 'nullable|string',
            'comentario' => 'nullable|string',
            'adjunto' => 'nullable|file|max:20480',
        ]);

        // Adjunto
        if ($request->hasFile('adjunto')) {
            $path = $request->file('adjunto')->store('calendario_editorial', 'public');
            $data['adjunto_path'] = $path;
            $data['adjunto_nombre'] = $request->file('adjunto')->getClientOriginalName();
        }

        $data['creado_por'] = Auth::id();
        $data['estado'] = 'pendiente';

        CalendarioEditorial::create($data);

        return redirect()
            ->route('calendario-editorial.index')
            ->with('success', 'Actividad registrada correctamente');
    }

    /* =========================
     |  FORM EDITAR
     ========================= */
    public function edit(CalendarioEditorial $calendarioEditorial)
    {
        // El calendario solo edita lo suyo
        if (
            Auth::user()->hasRole('calendario') &&
            $calendarioEditorial->creado_por !== Auth::id()
        ) {
            abort(403);
        }

        return view('calendario_editorial.edit', compact('calendarioEditorial'));
    }

    /* =========================
     |  ACTUALIZAR
     ========================= */
    public function update(Request $request, CalendarioEditorial $calendarioEditorial)
    {
        // Validación (estado ABIERTO, sin roles)
        $data = $request->validate([
            'semana' => 'required|integer',
            'dia' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'hora' => 'nullable',
            'tema' => 'required|string',
            'area' => 'nullable|string',
            'encabezado' => 'nullable|string',
            'contenido' => 'nullable|array',
            'publicar_en' => 'nullable|array',
            'etiquetas' => 'nullable|string',
            'comentario' => 'nullable|string',
            'enlace' => 'nullable|string',
            'estado' => 'required|in:pendiente,publicado,reprogramado,cancelado',
            'adjunto' => 'nullable|file|max:20480',
        ]);

        // Manejo de adjunto
        if ($request->hasFile('adjunto')) {
            if ($calendarioEditorial->adjunto_path) {
                Storage::disk('public')->delete($calendarioEditorial->adjunto_path);
            }

            $path = $request->file('adjunto')->store('calendario_editorial', 'public');
            $data['adjunto_path'] = $path;
            $data['adjunto_nombre'] = $request->file('adjunto')->getClientOriginalName();
        }

        // Si cambia a PUBLICADO, registrar metadata (una sola vez)
        if (
            $data['estado'] === 'publicado' &&
            $calendarioEditorial->estado !== 'publicado'
        ) {
            $data['fecha_publicado'] = now();
            $data['publicado_por'] = Auth::id();
        }

        // Actualizar
        $calendarioEditorial->update($data);

        return redirect()
            ->route('calendario-editorial.index')
            ->with('success', 'Actividad actualizada correctamente');
    }


    /* =========================
     |  MARCAR COMO PUBLICADO (ADMIN TI)
     ========================= */
    public function marcarPublicado(CalendarioEditorial $calendarioEditorial)
    {
        abort_unless(Auth::user()->hasRole('admin_ti'), 403);

        $calendarioEditorial->update([
            'estado' => 'publicado',
            'fecha_publicado' => now(),
            'publicado_por' => Auth::id(),
        ]);

        return back()->with('success', 'Actividad marcada como publicada');
    }

    public function show(CalendarioEditorial $calendarioEditorial)
    {
        return view('calendario_editorial.show', compact('calendarioEditorial'));
    }
}
