<?php

namespace App\Http\Controllers;

use App\Models\CalendarioEditorial;
use App\Models\CalendarioEditorialAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CalendarioEditorialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin_ti|gerencia|usuario|calendario']);
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
            'semana'            => 'required|integer',
            'dia'               => 'required|string',
            'fecha_publicacion' => 'required|date',
            'hora'              => 'nullable',
            'tema'              => 'required|string',
            'area'              => 'nullable|string',
            'encabezado'        => 'nullable|string',
            'contenido'         => 'nullable|array',
            'publicar_en'       => 'nullable|array',
            'etiquetas'         => 'nullable|string',
            'comentario'        => 'nullable|string',

            // UN SOLO SELECTOR, múltiples archivos
            'adjuntos'          => 'nullable|array',
            'adjuntos.*'        => 'file|max:20480',
        ]);

        $data['creado_por'] = Auth::id();
        $data['estado']     = 'pendiente';

        // Crear publicación
        $calendario = CalendarioEditorial::create($data);

        /* =========================
         |  GUARDAR ADJUNTOS
         ========================= */
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $index => $file) {

                $ruta = $file->store('calendario_editorial', 'public');

                // Primer archivo = adjunto principal (compatibilidad)
                if ($index === 0) {
                    $calendario->update([
                        'adjunto_path'   => $ruta,
                        'adjunto_nombre' => $file->getClientOriginalName(),
                    ]);
                }

                // Todos van a la tabla de adjuntos
                $calendario->adjuntos()->create([
                    'ruta'            => $ruta,
                    'nombre_original' => $file->getClientOriginalName(),
                    'mime_type'       => $file->getMimeType(),
                    'tamano'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('calendario-editorial.index')
            ->with('success', 'Actividad registrada correctamente');
    }

    /* =========================
     |  FORM EDITAR
     ========================= */
    public function edit(CalendarioEditorial $calendarioEditorial)
    {
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
        $data = $request->validate([
            'semana'            => 'required|integer',
            'dia'               => 'required|string',
            'fecha_publicacion' => 'required|date',
            'hora'              => 'nullable',
            'tema'              => 'required|string',
            'area'              => 'nullable|string',
            'encabezado'        => 'nullable|string',
            'contenido'         => 'nullable|array',
            'publicar_en'       => 'nullable|array',
            'etiquetas'         => 'nullable|string',
            'comentario'        => 'nullable|string',
            'estado'            => 'required|in:pendiente,publicado,reprogramado,cancelado',

            // 🔴 CLAVE: validar adjuntos también en editar
            'adjuntos'   => 'nullable|array',
            'adjuntos.*' => 'file|max:20480',
        ]);

        // Control de publicación
        if (
            $data['estado'] === 'publicado' &&
            $calendarioEditorial->estado !== 'publicado'
        ) {
            $data['fecha_publicado'] = now();
            $data['publicado_por']   = Auth::id();
        }

        // Actualizar datos principales
        $calendarioEditorial->update($data);

        /* =========================
     |  GUARDAR NUEVOS ADJUNTOS
     |  (SIN TOCAR LOS EXISTENTES)
     ========================= */
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {

                $ruta = $file->store('calendario_editorial', 'public');

                $calendarioEditorial->adjuntos()->create([
                    'ruta'            => $ruta,
                    'nombre_original' => $file->getClientOriginalName(),
                    'mime_type'       => $file->getMimeType(),
                    'tamano'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('calendario-editorial.index')
            ->with('success', 'Actividad actualizada correctamente');
    }



    public function destroyAdjunto(CalendarioEditorialAdjunto $adjunto)
    {
        $cal = $adjunto->calendarioEditorial; // relación inversa (la ponemos en el modelo adjunto abajo)

        // Seguridad:
        // - admin_ti puede borrar cualquiera
        // - rol calendario solo puede borrar los que él creó
        if (Auth::user()->hasRole('calendario') && $cal->creado_por !== Auth::id()) {
            abort(403);
        }

        // Eliminar archivo físico
        if ($adjunto->ruta) {
            Storage::disk('public')->delete($adjunto->ruta);
        }

        // Eliminar registro
        $adjunto->delete();

        return response()->json(['success' => true]);
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
