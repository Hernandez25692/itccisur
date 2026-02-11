<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Cobranza\{Empresa, Corte, Categoria, TipoEmpresa, CapitalRango, Propietario, TelefonoFijo, Celular, Correo};
use App\Services\Cobranza\CobranzaCalculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $q = Empresa::query()
            ->with(['corte', 'categoria', 'tipoEmpresa']);

        // 🔎 Buscar por nombre o RTN
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $q->where(function ($x) use ($buscar) {
                $x->where('nombre_empresa', 'like', "%$buscar%")
                    ->orWhere('rtn_empresa', 'like', "%$buscar%");
            });
        }

        // 📌 Estatus cobranza
        if ($request->filled('estatus')) {
            $q->where('estatus_cobranza', $request->estatus);
        }

        // 🌍 Ciudad
        if ($request->filled('ciudad')) {
            $q->where('ciudad', 'like', "%{$request->ciudad}%");
        }

        // 💳 Tipo de pago
        if ($request->filled('tipo_pago')) {
            $q->where('tipo_pago', $request->tipo_pago);
        }

        // 🏢 Tipo de empresa
        if ($request->filled('tipo_empresa_id')) {
            $q->where('tipo_empresa_id', $request->tipo_empresa_id);
        }

        // 🗂️ Categoría
        if ($request->filled('categoria_id')) {
            $q->where('categoria_id', $request->categoria_id);
        }

        // 📅 CORTE (NUEVO)
        if ($request->filled('corte_id')) {
            $q->where('corte_id', $request->corte_id);
        }

        $empresas = $q
            ->orderBy('nombre_empresa')
            ->paginate(12)
            ->withQueryString();

        // 🔽 Catálogos para filtros
        $tiposEmpresa = TipoEmpresa::where('activo', true)->orderBy('nombre')->get();
        $categorias   = Categoria::where('activo', true)->orderBy('nombre')->get();
        $cortes       = Corte::where('activo', true)->orderBy('dia_corte')->get();

        return view(
            'cobranza_socios.empresas.index',
            compact('empresas', 'tiposEmpresa', 'categorias', 'cortes')
        );
    }



    public function create()
    {
        $cortes = Corte::where('activo', true)->orderBy('dia_corte')->get();
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $tipos = TipoEmpresa::where('activo', true)->orderBy('nombre')->get();
        $rangos = CapitalRango::where('activo', true)->orderBy('capital_min')->get();

        return view('cobranza_socios.empresas.create', compact('cortes', 'categorias', 'tipos', 'rangos'));
    }

    public function store(Request $request, CobranzaCalculoService $calc)
    {
        $data = $request->validate([
            'nombre_empresa'      => 'required|string|max:255',
            'rtn_empresa'         => 'required|string|max:255|unique:cs_empresas,rtn_empresa',
            'rubro_actividad'     => 'nullable|string|max:255',

            'categoria_id'        => 'nullable|exists:cs_categorias,id',
            'tipo_empresa_id'     => 'nullable|exists:cs_tipos_empresa,id',

            'capital_declarado'   => 'required|numeric|min:0',

            // ❌ capital_rango_id NO se recibe del request
            // 'capital_rango_id' => eliminado a propósito

            'cuota_especial'      => 'nullable|numeric|min:0',

            'corte_id'            => 'required|exists:cs_cortes,id',
            'tipo_pago'           => 'required|in:mensual,bimensual,trimestral,semestral,anual',
            'fecha_ultimo_pago_historico' => 'nullable|date|before_or_equal:today',


            'direccion'           => 'nullable|string|max:255',
            'ciudad'              => 'nullable|string|max:255',
            'barrio_colonia'      => 'nullable|string|max:255',

            // 🔐 Validación geográfica REAL
            'latitud'             => 'nullable|numeric|between:-90,90',
            'longitud'            => 'nullable|numeric|between:-180,180',

            'gerente_adm'         => 'nullable|string|max:255',
            'gerente_rrhh'        => 'nullable|string|max:255',
            'gerente_contabilidad' => 'nullable|string|max:255',

            'comentario'          => 'nullable|string',

            // propietarios
            'propietarios'                 => 'nullable|array',
            'propietarios.*.nombre'        => 'required_with:propietarios|string|max:255',
            'propietarios.*.identidad'     => 'required_with:propietarios|string|max:50',
            'propietarios.*.rtn'           => 'nullable|string|max:50',

            // teléfonos
            'telefonos_fijos'      => 'nullable|array',
            'telefonos_fijos.*'    => 'nullable|string|max:50',

            'celulares'            => 'nullable|array',
            'celulares.*'          => 'nullable|string|max:50',

            'correos'              => 'nullable|array',
            'correos.*'            => 'nullable|email|max:255',
        ]);

        return DB::transaction(function () use ($data, $calc) {

            /**
             * =====================================================
             * 1️⃣ DETERMINAR RANGO DE CAPITAL AUTOMÁTICAMENTE
             * =====================================================
             */
            $rango = null;

            if (!empty($data['tipo_empresa_id']) && isset($data['capital_declarado'])) {
                $rango = $this->calcularRangoCapital(
                    (int) $data['tipo_empresa_id'],
                    (float) $data['capital_declarado']
                );
            }

            if (!$rango) {
                return back()
                    ->withErrors([
                        'capital_declarado' =>
                        'No existe un rango de capital válido para este tipo de empresa.'
                    ])
                    ->withInput();
            }

            if (!$rango) {
                return back()
                    ->withErrors([
                        'capital_declarado' =>
                        'No existe un rango de capital configurado para este monto.'
                    ])
                    ->withInput();
            }

            /**
             * =====================================================
             * 2️⃣ DEFINIR CUOTAS BASE
             * =====================================================
             */
            $cuotaBase = (float) $rango->cuota_mensual;
            $insBase   = (float) $rango->inscripcion;

            /**
             * =====================================================
             * 3️⃣ NORMALIZAR LAT / LONG
             * =====================================================
             */
            $data['latitud']  = $data['latitud']  ?? null;
            $data['longitud'] = $data['longitud'] ?? null;

            /**
             * =====================================================
             * 4️⃣ CREAR EMPRESA
             * =====================================================
             */
            $empresa = Empresa::create(array_merge($data, [
                'capital_rango_id'  => $rango->id,
                'cuota_base'        => $cuotaBase,
                'inscripcion_base'  => $insBase,
                'estado_empresa'    => 'activo',
            ]));

            /**
             * =====================================================
             * 5️⃣ PROPIETARIOS
             * =====================================================
             */
            foreach (($data['propietarios'] ?? []) as $p) {
                Propietario::create([
                    'empresa_id' => $empresa->id,
                    'nombre'     => $p['nombre'],
                    'identidad'  => $p['identidad'],
                    'rtn'        => $p['rtn'] ?? null,
                ]);
            }

            /**
             * =====================================================
             * 6️⃣ TELÉFONOS FIJOS
             * =====================================================
             */
            foreach (($data['telefonos_fijos'] ?? []) as $t) {
                if (trim((string) $t) !== '') {
                    TelefonoFijo::create([
                        'empresa_id' => $empresa->id,
                        'telefono'   => $t
                    ]);
                }
            }

            /**
             * =====================================================
             * 7️⃣ CELULARES
             * =====================================================
             */
            foreach (($data['celulares'] ?? []) as $c) {
                if (trim((string) $c) !== '') {
                    Celular::create([
                        'empresa_id' => $empresa->id,
                        'celular'    => $c
                    ]);
                }
            }

            /**
             * =====================================================
             * 8️⃣ CORREOS
             * =====================================================
             */
            foreach (($data['correos'] ?? []) as $e) {
                if (trim((string) $e) !== '') {
                    Correo::create([
                        'empresa_id' => $empresa->id,
                        'correo'     => $e
                    ]);
                }
            }

            /**
             * =====================================================
             * 9️⃣ RECÁLCULO AUTOMÁTICO (CORTE / MORA / ESTATUS)
             * =====================================================
             */
            $empresa->load(['corte']);
            $calc->recalcularEmpresa($empresa);

            /**
             * =====================================================
             * 🔚 FIN
             * =====================================================
             */
            return redirect()
                ->route('cobranza.empresas.show', $empresa)
                ->with('success', 'Empresa creada correctamente.');
        });
    }

    private function calcularRangoCapital(int $tipoEmpresaId, float $capital): ?CapitalRango
    {
        return CapitalRango::where('activo', true)
            ->where('tipo_empresa_id', $tipoEmpresaId)
            ->where('capital_min', '<=', $capital)
            ->where('capital_max', '>=', $capital)
            ->orderBy('capital_min')
            ->first();
    }

    public function show(Empresa $empresa, CobranzaCalculoService $calc)
    {
        // ✅ Garantiza estado de cuenta y mora sincronizados al abrir la ficha
        $empresa->loadMissing('corte');
        $calc->recalcularEmpresa($empresa);

        $empresa->load([
            'corte',
            'categoria',
            'tipoEmpresa',
            'capitalRango',
            'propietarios',
            'telefonosFijos',
            'celulares',
            'correos',
            'cargos' => fn($q) => $q->orderBy('fecha_vencimiento', 'desc'),
            'pagos' => fn($q) => $q->orderBy('fecha_pago', 'desc'),
        ]);

        $pendientes = $empresa->cargos->where('estado', 'pendiente')->values();
        $pagados = $empresa->cargos->where('estado', 'pagado')->values();

        return view('cobranza_socios.empresas.show', compact('empresa', 'pendientes', 'pagados'));
    }


    public function edit(Empresa $empresa)
    {
        $empresa->load(['propietarios', 'telefonosFijos', 'celulares', 'correos']);

        $cortes = Corte::where('activo', true)->orderBy('dia_corte')->get();
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $tipos = TipoEmpresa::where('activo', true)->orderBy('nombre')->get();
        $rangos = CapitalRango::where('activo', true)->orderBy('capital_min')->get();

        return view('cobranza_socios.empresas.edit', compact('empresa', 'cortes', 'categorias', 'tipos', 'rangos'));
    }

    public function update(Request $request, Empresa $empresa, CobranzaCalculoService $calc)
    {
        $data = $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'rtn_empresa' => 'required|string|max:255|unique:cs_empresas,rtn_empresa,' . $empresa->id,
            'rubro_actividad' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|exists:cs_categorias,id',
            'tipo_empresa_id' => 'nullable|exists:cs_tipos_empresa,id',
            'capital_declarado' => 'nullable|numeric|min:0',
            'cuota_especial' => 'nullable|numeric|min:0',
            'corte_id' => 'required|exists:cs_cortes,id',
            'tipo_pago' => 'required|in:mensual,bimensual,trimestral,semestral,anual',
            'fecha_ultimo_pago_historico' => 'nullable|date|before_or_equal:today',


            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'barrio_colonia' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',

            'gerente_adm' => 'nullable|string|max:255',
            'gerente_rrhh' => 'nullable|string|max:255',
            'gerente_contabilidad' => 'nullable|string|max:255',

            'comentario' => 'nullable|string',

            'propietarios' => 'nullable|array',
            'propietarios.*.nombre' => 'required_with:propietarios|string|max:255',
            'propietarios.*.identidad' => 'required_with:propietarios|string|max:50',
            'propietarios.*.rtn' => 'nullable|string|max:50',

            'telefonos_fijos' => 'nullable|array',
            'telefonos_fijos.*' => 'nullable|string|max:50',

            'celulares' => 'nullable|array',
            'celulares.*' => 'nullable|string|max:50',

            'correos' => 'nullable|array',
            'correos.*' => 'nullable|email|max:255',

            // inactivación
            'marcar_inactiva' => 'nullable|boolean',
            'motivo_inactivacion' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($data, $empresa, $calc) {

            // recalcular cuota_base/inscripcion si cambió rango
            $rango = null;

            if (!empty($data['tipo_empresa_id']) && isset($data['capital_declarado'])) {
                $rango = $this->calcularRangoCapital(
                    (int) $data['tipo_empresa_id'],
                    (float) $data['capital_declarado']
                );
            }

            if (!$rango) {
                return back()
                    ->withErrors([
                        'capital_declarado' =>
                        'No existe un rango de capital válido para este tipo de empresa.'
                    ])
                    ->withInput();
            }

            $cuotaBase = (float) $rango->cuota_mensual;
            $insBase   = (float) $rango->inscripcion;


            $empresa->update(array_merge($data, [
                'capital_rango_id' => $rango->id,
                'cuota_base' => $cuotaBase,
                'inscripcion_base' => $insBase,
            ]));


            // reset relaciones múltiples (simple y exacto)
            $empresa->propietarios()->delete();
            $empresa->telefonosFijos()->delete();
            $empresa->celulares()->delete();
            $empresa->correos()->delete();

            foreach (($data['propietarios'] ?? []) as $p) {
                Propietario::create([
                    'empresa_id' => $empresa->id,
                    'nombre' => $p['nombre'],
                    'identidad' => $p['identidad'],
                    'rtn' => $p['rtn'] ?? null,
                ]);
            }

            foreach (($data['telefonos_fijos'] ?? []) as $t) {
                if (trim((string)$t) !== '') {
                    TelefonoFijo::create(['empresa_id' => $empresa->id, 'telefono' => $t]);
                }
            }

            foreach (($data['celulares'] ?? []) as $c) {
                if (trim((string)$c) !== '') {
                    Celular::create(['empresa_id' => $empresa->id, 'celular' => $c]);
                }
            }

            foreach (($data['correos'] ?? []) as $e) {
                if (trim((string)$e) !== '') {
                    Correo::create(['empresa_id' => $empresa->id, 'correo' => $e]);
                }
            }

            // Inactivar -> mover a histórico y eliminar
            if (!empty($data['marcar_inactiva'])) {
                DB::table('cs_empresas_inactivas')->insert([
                    'empresa_original_id' => $empresa->id,
                    'fecha_inactivacion' => now()->toDateString(),
                    'motivo_inactivacion' => $data['motivo_inactivacion'] ?? null,
                    'inactivado_por' => Auth::id(),
                    'nombre_empresa' => $empresa->nombre_empresa,
                    'rtn_empresa' => $empresa->rtn_empresa,
                    'ciudad' => $empresa->ciudad,
                    'barrio_colonia' => $empresa->barrio_colonia,
                    'direccion' => $empresa->direccion,
                    'latitud' => $empresa->latitud,
                    'longitud' => $empresa->longitud,
                    'comentario' => $empresa->comentario,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $empresa->delete();

                return redirect()->route('cobranza.empresas.index')->with('success', 'Empresa inactivada y enviada a histórico.');
            }

            $empresa->load('corte');
            $calc->recalcularEmpresa($empresa);

            return redirect()->route('cobranza.empresas.show', $empresa)->with('success', 'Empresa actualizada correctamente.');
        });
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('cobranza.empresas.index')->with('success', 'Empresa eliminada.');
    }

    /**
     * Estado de cuenta (vista HTML)
     */
    public function estadoCuenta(Empresa $empresa)
    {
        // 🔄 Asegurar cargos actualizados
        app(\App\Services\Cobranza\CobranzaCalculoService::class)
            ->recalcularEmpresa($empresa);

        $cargos = $empresa->cargos()
            ->whereIn('estado', ['pendiente', 'pagado'])
            ->orderBy('periodo_inicio')
            ->get();

        $filas = $cargos->map(function ($cargo) {
            $inicio = \Carbon\Carbon::parse($cargo->periodo_inicio);
            $fin    = \Carbon\Carbon::parse($cargo->periodo_fin);

            Carbon::setLocale('es');

            return [
                'periodo_texto' => mb_convert_case(
                    $inicio->translatedFormat('F Y'),
                    MB_CASE_TITLE,
                    'UTF-8'
                ) . ' – ' . mb_convert_case(
                    $fin->translatedFormat('F Y'),
                    MB_CASE_TITLE,
                    'UTF-8'
                ),

                'anio'   => $inicio->year,
                'mes'    => $inicio->month,
                'dias'   => $inicio->diffInDays($fin) + 1,
                'monto'  => (float) $cargo->total,
                'estado' => $cargo->estado, // pendiente | pagado
            ];
        });


        $totalAdeuda = $cargos
            ->where('estado', 'pendiente')
            ->sum('total');

        return view(
            'cobranza_socios.empresas.estado_cuenta',
            compact('empresa', 'filas', 'totalAdeuda')
        );
    }

    /**
     * Estado de cuenta (PDF)
     */
    public function estadoCuentaPdf(Empresa $empresa)
    {
        // 🔄 Asegurar cargos actualizados
        app(\App\Services\Cobranza\CobranzaCalculoService::class)
            ->recalcularEmpresa($empresa);

        $cargos = $empresa->cargos()
            ->whereIn('estado', ['pendiente', 'pagado'])
            ->orderBy('periodo_inicio')
            ->get();

        $filas = $cargos->map(function ($cargo) {
            $inicio = \Carbon\Carbon::parse($cargo->periodo_inicio);
            $fin    = \Carbon\Carbon::parse($cargo->periodo_fin);

            return [
                'periodo_texto' => ucfirst(
                    $inicio->translatedFormat('F Y')
                        . ' – ' .
                        $fin->translatedFormat('F Y')
                ),
                'anio'   => $inicio->year,
                'mes'    => $inicio->month,
                'dias'   => $inicio->diffInDays($fin) + 1,
                'monto'  => (float) $cargo->total,
                'estado' => $cargo->estado, // pendiente | pagado
            ];
        });


        $totalAdeuda = $cargos
            ->where('estado', 'pendiente')
            ->sum('total');

        $pdf = Pdf::loadView(
            'cobranza_socios.empresas.estado_cuenta_pdf',
            compact('empresa', 'filas', 'totalAdeuda')
        )->setPaper('letter', 'landscape');

        return $pdf->download(
            'Estado_Cuenta_' . preg_replace('/\s+/', '_', $empresa->nombre_empresa) . '.pdf'
        );
    }
}
