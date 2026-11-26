<?php

namespace App\Http\Controllers;

use App\Models\CalendarioEditorial;
use App\Models\CalendarioEditorialAdjunto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CalendarioEditorialController extends Controller
{
    private const FILTROS = ['buscar', 'area', 'red', 'tipo', 'creado_por'];

    /** Tipos de adjunto permitidos (sin HTML/SVG/ejecutables: se sirven desde el sistema). */
    private const MIMES_ADJUNTOS = 'jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar';

    public function __construct()
    {
        $this->middleware(['auth', 'role:admin_ti|gerencia|usuario|calendario']);
    }

    public function index(Request $request)
    {
        $filtros = $request->only(self::FILTROS);

        $base = fn () => CalendarioEditorial::query()
            ->filtrar($filtros)
            ->semana($request->semana)
            ->mes($request->mes)
            ->anio($request->anio);

        // Orden en el servidor (antes se reordenaba solo la página visible)
        $orden = $request->orden === 'asc' ? 'asc' : 'desc';

        $registros = $base()
            ->estado($request->estado)
            ->with('creador', 'publicadoPor')
            ->orderBy('fecha_publicacion', $orden)
            ->orderBy('hora', $orden)
            ->paginate(10)
            ->withQueryString();

        // Contadores por estado con los mismos filtros (menos el de estado)
        $porEstado = $base()
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $vencidas = $base()->vencidas()->count();

        $anios = CalendarioEditorial::selectRaw('DISTINCT ' . $this->sqlAnio() . ' as anio')
            ->orderByDesc('anio')
            ->pluck('anio')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        $creadores = User::whereIn('id', CalendarioEditorial::select('creado_por')->whereNotNull('creado_por')->distinct())
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('calendario_editorial.index', compact('registros', 'porEstado', 'vencidas', 'anios', 'creadores'));
    }

    public function create(Request $request)
    {
        // Desde la vista calendario se puede llegar con el día ya elegido
        $fechaSugerida = $this->fechaDeQuery($request, 'fecha');

        return view('calendario_editorial.create', compact('fechaSugerida'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->reglas());

        $data['creado_por'] = Auth::id();
        $data['estado']     = 'pendiente';

        // Crear publicación
        $calendario = CalendarioEditorial::create($data);

        $this->guardarAdjuntos($request, $calendario);

        return redirect()
            ->route('calendario-editorial.index')
            ->with('success', 'Actividad registrada correctamente');
    }

    public function edit(CalendarioEditorial $calendarioEditorial)
    {
        abort_unless($calendarioEditorial->puedeEditar(Auth::user()), 403);

        $calendarioEditorial->load('adjuntos');

        return view('calendario_editorial.edit', compact('calendarioEditorial'));
    }

    public function update(Request $request, CalendarioEditorial $calendarioEditorial)
    {
        abort_unless($calendarioEditorial->puedeEditar(Auth::user()), 403);

        $esAdmin = Auth::user()->hasRole('admin_ti');

        if ($esAdmin && $request->filled('enlace') && !preg_match('#^https?://#i', $request->enlace)) {
            $request->merge(['enlace' => 'https://' . ltrim($request->enlace, '/')]);
        }

        // Estado, enlace y nota administrativa solo los cambia admin_ti
        $reglas = $this->reglas() + ($esAdmin ? [
            'estado'     => ['required', Rule::in(CalendarioEditorial::ESTADOS)],
            'enlace'     => 'nullable|url:http,https|max:2048',
            'nota_admin' => 'nullable|string',
        ] : []);

        $data = $request->validate($reglas);

        // Si se desmarcan todas las casillas, el navegador no manda el campo
        $data['contenido']   = $data['contenido'] ?? null;
        $data['publicar_en'] = $data['publicar_en'] ?? null;

        // Control de publicación
        if (
            ($data['estado'] ?? null) === 'publicado' &&
            $calendarioEditorial->estado !== 'publicado'
        ) {
            $data['fecha_publicado'] = now();
            $data['publicado_por']   = Auth::id();
        }

        // Actualizar datos principales
        $calendarioEditorial->update($data);

        $this->guardarAdjuntos($request, $calendarioEditorial);

        return redirect()
            ->route('calendario-editorial.index')
            ->with('success', 'Actividad actualizada correctamente');
    }

    public function verAdjunto(CalendarioEditorialAdjunto $adjunto)
    {
        return $this->mostrarArchivo($adjunto->ruta);
    }

    public function destroyAdjunto(CalendarioEditorialAdjunto $adjunto)
    {
        $cal = $adjunto->calendario;

        // Mismo permiso que para editar la publicación
        abort_unless($cal && $cal->puedeEditar(Auth::user()), 403);

        // Eliminar archivo físico
        $this->eliminarArchivo($adjunto->ruta);

        // Eliminar registro
        $adjunto->delete();

        // El adjunto "principal" (columna antigua) pasa al siguiente que quede
        if ($cal->adjunto_path === $adjunto->ruta) {
            $siguiente = $cal->adjuntos()->oldest()->first();
            $cal->update([
                'adjunto_path'   => $siguiente?->ruta,
                'adjunto_nombre' => $siguiente?->nombre_original,
            ]);
        }

        return response()->json(['success' => true]);
    }

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
        $calendarioEditorial->load('adjuntos', 'creador', 'publicadoPor');

        return view('calendario_editorial.show', compact('calendarioEditorial'));
    }

    public function calendar()
    {
        return view('calendario_editorial.calendar');
    }

    /**
     * Feed JSON para FullCalendar: solo el rango visible (start/end) y con los
     * mismos filtros del listado.
     */
    public function eventos(Request $request)
    {
        $inicio = $this->fechaDeQuery($request, 'start') ?? now()->startOfMonth()->subWeek()->toDateString();
        $fin    = $this->fechaDeQuery($request, 'end') ?? now()->endOfMonth()->addWeek()->toDateString();

        $eventos = CalendarioEditorial::query()
            ->filtrar($request->only(self::FILTROS))
            ->estado($request->estado)
            ->whereDate('fecha_publicacion', '>=', $inicio)
            ->whereDate('fecha_publicacion', '<', $fin)
            ->orderBy('fecha_publicacion')
            ->orderBy('hora')
            ->get()
            ->map(fn (CalendarioEditorial $e) => [
                'id'              => $e->id,
                'title'           => $e->tema ?: 'Sin título',
                'start'           => $e->fecha_publicacion->format('Y-m-d') . ($e->hora ? 'T' . substr($e->hora, 0, 5) : ''),
                'allDay'          => !$e->hora,
                'url'             => route('calendario-editorial.show', $e),
                'backgroundColor' => $e->color,
                'borderColor'     => $e->color,
                'extendedProps'   => [
                    'estado' => $e->estado,
                    'area'   => $e->area,
                    'redes'  => $e->publicar_en ?? [],
                    'hora'   => $e->hora ? substr($e->hora, 0, 5) : null,
                ],
            ]);

        return response()->json($eventos);
    }

    private function reglas(): array
    {
        return [
            'semana'            => 'nullable|integer|min:1|max:53',
            'dia'               => 'nullable|string', // se recalcula desde la fecha
            'fecha_publicacion' => 'required|date',
            'hora'              => 'nullable|date_format:H:i,H:i:s',
            'tema'              => 'required|string',
            'area'              => 'nullable|string|max:120',
            'encabezado'        => 'nullable|string',
            'contenido'         => 'nullable|array',
            'contenido.*'       => ['string', Rule::in(CalendarioEditorial::TIPOS_CONTENIDO)],
            'publicar_en'       => 'nullable|array',
            'publicar_en.*'     => ['string', Rule::in(CalendarioEditorial::REDES)],
            'etiquetas'         => 'nullable|string',
            'comentario'        => 'nullable|string',
            'adjuntos'          => 'nullable|array',
            'adjuntos.*'        => 'file|max:20480|mimes:' . self::MIMES_ADJUNTOS,
        ];
    }

    private function guardarAdjuntos(Request $request, CalendarioEditorial $calendario): void
    {
        if (!$request->hasFile('adjuntos')) {
            return;
        }

        foreach ($request->file('adjuntos') as $file) {
            $ruta = $file->store('calendario_editorial', 'local');

            // El primero queda como adjunto principal (compatibilidad con registros antiguos)
            if (!$calendario->adjunto_path) {
                $calendario->update([
                    'adjunto_path'   => $ruta,
                    'adjunto_nombre' => $file->getClientOriginalName(),
                ]);
            }

            $calendario->adjuntos()->create([
                'ruta'            => $ruta,
                'nombre_original' => $file->getClientOriginalName(),
                'mime_type'       => $file->getMimeType(),
                'tamano'          => $file->getSize(),
            ]);
        }
    }

    /** Fecha Y-m-d de un parámetro de la URL, o null si no viene o no es una fecha. */
    private function fechaDeQuery(Request $request, string $clave): ?string
    {
        if (!$request->filled($clave)) {
            return null;
        }

        return rescue(fn () => Carbon::parse($request->query($clave))->toDateString(), null, false);
    }

    private function sqlAnio(): string
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%Y', fecha_publicacion) AS INTEGER)"
            : 'YEAR(fecha_publicacion)';
    }
}
