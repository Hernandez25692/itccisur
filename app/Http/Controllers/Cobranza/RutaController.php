<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\{Ruta, Empresa};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::query()->orderBy('fecha_ruta', 'desc')->paginate(12);
        return view('cobranza_socios.rutas.index', compact('rutas'));
    }

    public function create()
    {
        // Solo empresas activas
        $empresas = Empresa::orderBy('nombre_empresa')->get();
        return view('cobranza_socios.rutas.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_ruta' => 'required|date',
            'gestor_id' => 'required|exists:users,id',
            'empresa_ids' => 'required|array|min:1',
            'empresa_ids.*' => 'exists:cs_empresas,id',
            'comentario' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data) {
            $ruta = Ruta::create([
                'nombre' => $data['nombre'],
                'fecha_ruta' => $data['fecha_ruta'],
                'gestor_id' => $data['gestor_id'],
                'comentario' => $data['comentario'] ?? null,
                'estado' => 'planificada',
            ]);

            $orden = 1;
            foreach ($data['empresa_ids'] as $eid) {
                DB::table('cs_ruta_empresas')->insert([
                    'ruta_id' => $ruta->id,
                    'empresa_id' => $eid,
                    'orden' => $orden++,
                    'estado_visita' => 'pendiente',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('cobranza.rutas.show', $ruta)->with('success', 'Ruta creada.');
        });
    }

    public function show(Ruta $ruta)
    {
        $ruta->load(['empresas' => function ($q) {
            $q->orderBy('cs_ruta_empresas.orden')
                ->select('cs_empresas.*')
                ->addSelect([
                    'cs_ruta_empresas.orden as pivot_orden',
                    'cs_ruta_empresas.estado_visita as pivot_estado_visita',
                    'cs_ruta_empresas.nota_visita as pivot_nota_visita',
                    'cs_ruta_empresas.gestor_id as pivot_gestor_id',
                ]);
        }]);

        // ✅ Inyectar nombre del gestor en el pivote (sin romper relaciones)
        $gestores = DB::table('users')
            ->select('id', 'name')
            ->pluck('name', 'id');

        foreach ($ruta->empresas as $e) {
            $e->pivot->gestor_nombre = $e->pivot->gestor_id ? ($gestores[$e->pivot->gestor_id] ?? '—') : null;
        }

        return view('cobranza_socios.rutas.show', compact('ruta'));
    }


    public function ordenarPorCercania(Ruta $ruta)
    {
        $ruta->load('empresas');

        $empresas = $ruta->empresas->filter(fn($e) => $e->latitud && $e->longitud)->values();
        if ($empresas->count() < 2) {
            return back()->with('error', 'Se requieren al menos 2 empresas con coordenadas para ordenar por cercanía.');
        }

        // vecino más cercano
        $ordenadas = [];
        $actual = $empresas->first();
        $pendientes = $empresas->slice(1)->values();

        $ordenadas[] = $actual;

        while ($pendientes->count() > 0) {
            $idx = 0;
            $mejorDist = PHP_FLOAT_MAX;

            foreach ($pendientes as $i => $cand) {
                $d = $this->dist($actual->latitud, $actual->longitud, $cand->latitud, $cand->longitud);
                if ($d < $mejorDist) {
                    $mejorDist = $d;
                    $idx = $i;
                }
            }

            $actual = $pendientes[$idx];
            $ordenadas[] = $actual;
            $pendientes = $pendientes->forget($idx)->values();
        }

        // actualizar orden en tabla pivote
        DB::transaction(function () use ($ruta, $ordenadas) {
            $orden = 1;
            foreach ($ordenadas as $e) {
                DB::table('cs_ruta_empresas')
                    ->where('ruta_id', $ruta->id)
                    ->where('empresa_id', $e->id)
                    ->update(['orden' => $orden++, 'updated_at' => now()]);
            }
        });

        return back()->with('success', 'Ruta ordenada por cercanía (heurística).');
    }

    public function check(Ruta $ruta, Empresa $empresa, Request $request)
    {
        $data = $request->validate([
            'estado_visita' => 'required|in:pendiente,visitado,no_encontrado,reprogramado',
            'nota_visita' => 'nullable|string',
        ]);

        DB::table('cs_ruta_empresas')
            ->where('ruta_id', $ruta->id)
            ->where('empresa_id', $empresa->id)
            ->update([
                'estado_visita' => $data['estado_visita'],
                'nota_visita' => $data['nota_visita'] ?? null,
                'checked_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Visita actualizada.');
    }

    private function dist($lat1, $lon1, $lat2, $lon2): float
    {
        // distancia aproximada (Haversine simplificada) en KM
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    public function sugerir(Request $request)
    {
        $data = $request->validate([
            'fecha_ruta' => 'required|date',
            'ventana_dias_cobro' => 'required|integer|min:1|max:30',
            'solo_con_coordenadas' => 'boolean',
            'incluir_en_mora' => 'boolean',
        ]);

        return DB::transaction(function () use ($data) {

            // 1️⃣ Crear la ruta
            $ruta = Ruta::create([
                'nombre' => 'Ruta sugerida ' . $data['fecha_ruta'],
                'fecha_ruta' => $data['fecha_ruta'],
                'gestor_id' => null,
                'estado' => 'sugerida',
                'ventana_dias_cobro' => $data['ventana_dias_cobro'],
                'solo_con_coordenadas' => $data['solo_con_coordenadas'] ?? true,
                'incluir_en_mora' => $data['incluir_en_mora'] ?? true,
                'creado_por' => Auth::id(),
            ]);

            // 2️⃣ Definir ventana de cobro
            $ventana = (int) $data['ventana_dias_cobro'];

            $desde = now()->subDays($ventana);
            $hasta = now()->addDays($ventana);


            // 3️⃣ Buscar empresas candidatas
            $empresas = Empresa::query()
                ->where('estado_empresa', 'activo')
                ->when($data['solo_con_coordenadas'] ?? true, function ($q) {
                    $q->whereNotNull('latitud')->whereNotNull('longitud');
                })
                ->when($data['incluir_en_mora'] ?? true, function ($q) use ($desde, $hasta) {
                    $q->where(function ($x) use ($desde, $hasta) {
                        $x->where('estatus_cobranza', 'en_mora')
                            ->orWhereBetween('proxima_fecha_cobro', [$desde, $hasta]);
                    });
                })
                ->orderBy('nombre_empresa')
                ->get();

            // 4️⃣ Insertar empresas en la ruta
            $orden = 1;
            foreach ($empresas as $empresa) {
                DB::table('cs_ruta_empresas')->insert([
                    'ruta_id' => $ruta->id,
                    'empresa_id' => $empresa->id,
                    'orden' => $orden++,
                    'estado_visita' => 'pendiente',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()
                ->route('cobranza.rutas.show', $ruta)
                ->with('success', 'Ruta sugerida generada con ' . $empresas->count() . ' empresas.');
        });
    }


    public function confirmar(Ruta $ruta)
    {
        if ($ruta->estado !== 'sugerida') {
            return back()->with('error', 'Solo se pueden confirmar rutas sugeridas.');
        }

        $ruta->update([
            'estado' => 'asignada',
            'confirmado_por' => Auth::id(),
            'confirmado_en' => now(),
        ]);

        return back()->with('success', 'Ruta confirmada y lista para asignar.');
    }

    public function asignarGestores(Ruta $ruta)
    {
        if ($ruta->estado !== 'sugerida') {
            return back()->with('error', 'Solo se pueden asignar gestores a rutas sugeridas.');
        }

        // 1️⃣ Obtener gestores de cobranza
        $gestores = \App\Models\User::role('Cobranza')->get();


        if ($gestores->isEmpty()) {
            return back()->with('error', 'No hay gestores disponibles.');
        }

        // 2️⃣ Empresas de la ruta
        $empresas = $ruta->empresas()->orderBy('orden')->get();

        if ($empresas->isEmpty()) {
            return back()->with('error', 'La ruta no tiene empresas.');
        }

        // 3️⃣ Reparto equitativo (round-robin)
        $totalGestores = $gestores->count();
        $i = 0;

        DB::transaction(function () use ($ruta, $empresas, $gestores, $totalGestores, &$i) {
            foreach ($empresas as $empresa) {
                $gestor = $gestores[$i % $totalGestores];

                DB::table('cs_ruta_empresas')
                    ->where('ruta_id', $ruta->id)
                    ->where('empresa_id', $empresa->id)
                    ->update([
                        'gestor_id' => $gestor->id,
                        'updated_at' => now(),
                    ]);

                $i++;
            }

            $ruta->update([
                'estado' => 'asignada',
                'confirmado_por' => Auth::id(),
                'confirmado_en' => now(),
            ]);
        });

        return back()->with('success', 'Gestores asignados correctamente.');
    }


    public function misRutas()
    {
        $userId = Auth::id();

        // Rutas donde el usuario aparece asignado en la pivote
        $rutas = Ruta::query()
            ->whereExists(function ($q) use ($userId) {
                $q->select(DB::raw(1))
                    ->from('cs_ruta_empresas')
                    ->whereColumn('cs_ruta_empresas.ruta_id', 'cs_rutas.id')
                    ->where('cs_ruta_empresas.gestor_id', $userId);
            })
            ->orderBy('fecha_ruta', 'desc')
            ->paginate(12);

        return view('cobranza_socios.rutas.mis', compact('rutas'));
    }

    public function miRuta(Ruta $ruta)
    {
        $userId = Auth::id();

        // Traer solo empresas asignadas a este gestor dentro de esta ruta
        $empresas = $ruta->empresas()
            ->wherePivot('gestor_id', $userId)
            ->orderBy('cs_ruta_empresas.orden')
            ->get();

        // Seguridad: si no tiene empresas asignadas, no debería ver nada
        if ($empresas->isEmpty()) {
            abort(403, 'No tienes empresas asignadas en esta ruta.');
        }

        return view('cobranza_socios.rutas.mi_ruta', compact('ruta', 'empresas'));
    }

    public function reasignarEmpresa(Request $request, Ruta $ruta)
    {
        $data = $request->validate([
            'empresa_id' => 'required|exists:cs_empresas,id',
            'gestor_id'  => 'nullable|exists:users,id',
        ]);

        DB::table('cs_ruta_empresas')
            ->where('ruta_id', $ruta->id)
            ->where('empresa_id', $data['empresa_id'])
            ->update([
                'gestor_id' => $data['gestor_id'],
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Empresa reasignada correctamente.');
    }
}
