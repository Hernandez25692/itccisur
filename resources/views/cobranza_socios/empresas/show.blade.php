<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-8">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $empresa->nombre_empresa }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    RTN: {{ $empresa->rtn_empresa }} ·
                    {{ $empresa->tipoEmpresa?->nombre ?? '—' }} ·
                    {{ strtoupper($empresa->tipo_pago) }}
                </p>
                <p class="text-xs text-gray-400">
                    Categoría: {{ $empresa->categoria?->nombre ?? '—' }} ·
                    Rubro: {{ $empresa->rubro_actividad ?? '—' }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('cobranza.empresas.edit', $empresa) }}"
                    class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Editar
                </a>

                <a href="{{ route('cobranza.pagos.create', $empresa) }}"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    Registrar pago
                </a>

                <a href="{{ route('cobranza.empresas.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Volver
                </a>
            </div>
        </div>

        {{-- ================= ALERTAS ================= --}}
        @if (session('success'))
            <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= BLOQUE COBRANZA ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Estatus de cobranza</p>
                <p
                    class="text-xl font-bold mt-1
                    {{ $empresa->estatus_cobranza === 'en_mora' ? 'text-red-700' : 'text-green-700' }}">
                    {{ $empresa->estatus_cobranza === 'en_mora' ? 'EN MORA' : 'AL DÍA' }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Próximo cobro:
                    <span class="font-semibold text-gray-900">
                        {{ $empresa->proxima_fecha_cobro?->format('d/m/Y') ?? '—' }}
                    </span>
                </p>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Mora acumulada</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    L. {{ number_format($empresa->valor_mora, 2) }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Períodos vencidos
                    <span class="text-xs text-gray-400">
                        ({{ strtoupper($empresa->tipo_pago) }})
                    </span>:
                    <span class="font-semibold text-gray-900">
                        {{ $empresa->meses_mora }}
                    </span>
                </p>

            </div>

            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Observación</p>
                <p
                    class="text-xl font-bold mt-1
                    {{ $empresa->observacion_cobro === 'incobrable' ? 'text-amber-700' : 'text-gray-900' }}">
                    {{ strtoupper($empresa->observacion_cobro) }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Cuota aplicada:
                    <span class="font-semibold text-gray-900">
                        L. {{ number_format($empresa->cuota_aplicada, 2) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- ================= INFORMACIÓN ADMINISTRATIVA ================= --}}
        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-900 mb-4">Información administrativa</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Capital declarado</p>
                    <p class="font-semibold text-gray-900">
                        L. {{ number_format($empresa->capital_declarado ?? 0, 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Cuota base</p>
                    <p class="font-semibold text-gray-900">
                        L. {{ number_format($empresa->cuota_base, 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Inscripción base</p>
                    <p class="font-semibold text-gray-900">
                        L. {{ number_format($empresa->inscripcion_base, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Gerente administrativo</p>
                    <p class="font-semibold text-gray-900">{{ $empresa->gerente_adm ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Gerente RRHH</p>
                    <p class="font-semibold text-gray-900">{{ $empresa->gerente_rrhh ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Gerente contabilidad</p>
                    <p class="font-semibold text-gray-900">{{ $empresa->gerente_contabilidad ?? '—' }}</p>
                </div>
            </div>

            @if ($empresa->comentario)
                <div class="mt-4 text-sm">
                    <p class="text-gray-500">Comentario interno</p>
                    <p class="text-gray-900">{{ $empresa->comentario }}</p>
                </div>
            @endif
        </div>

        {{-- ================= UBICACIÓN ================= --}}
        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-900 mb-3">Ubicación</h2>
            <p class="text-sm text-gray-700">{{ $empresa->direccion ?? '—' }}</p>
            <p class="text-sm text-gray-500">
                {{ $empresa->ciudad ?? '—' }} · {{ $empresa->barrio_colonia ?? '—' }}
            </p>

            @if ($empresa->latitud && $empresa->longitud)
                <a target="_blank"
                    href="https://www.google.com/maps?q={{ $empresa->latitud }},{{ $empresa->longitud }}"
                    class="inline-block mt-2 text-blue-600 hover:underline text-sm">
                    Abrir en Google Maps
                </a>
            @endif
        </div>

        {{-- ================= CONTACTOS Y PROPIETARIOS ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border p-6">
                <h2 class="font-bold text-gray-900 mb-3">Contactos</h2>

                <p class="font-semibold text-gray-700">Teléfonos fijos</p>
                <ul class="list-disc pl-5 text-sm mb-3">
                    @forelse($empresa->telefonosFijos as $t)
                        <li>{{ $t->telefono }}</li>
                    @empty
                        <li class="text-gray-400">—</li>
                    @endforelse
                </ul>

                <p class="font-semibold text-gray-700">Celulares</p>
                <ul class="list-disc pl-5 text-sm mb-3">
                    @forelse($empresa->celulares as $c)
                        <li>{{ $c->celular }}</li>
                    @empty
                        <li class="text-gray-400">—</li>
                    @endforelse
                </ul>

                <p class="font-semibold text-gray-700">Correos</p>
                <ul class="list-disc pl-5 text-sm">
                    @forelse($empresa->correos as $e)
                        <li>{{ $e->correo }}</li>
                    @empty
                        <li class="text-gray-400">—</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h2 class="font-bold text-gray-900 mb-3">Propietarios</h2>
                <div class="space-y-2">
                    @forelse($empresa->propietarios as $p)
                        <div class="border rounded-xl p-3">
                            <p class="font-semibold text-gray-900">{{ $p->nombre }}</p>
                            <p class="text-sm text-gray-500">
                                Identidad: {{ $p->identidad }}
                                @if ($p->rtn)
                                    · RTN: {{ $p->rtn }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">—</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border overflow-hidden">
            <div class="p-5 border-b flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Estado de cuenta (Cargos)</h2>

                <form class="flex items-center gap-2" method="POST" action="{{ route('cobranza.cargos.generar') }}">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                    <input name="cantidad" type="number" min="1" max="24" value="1"
                        class="w-24 rounded-xl border-gray-300">
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white hover:bg-black">
                        Generar cargos adelantados
                    </button>
                    <p class="text-xs text-gray-500 mt-2">
                        Los cargos vencidos se generan automáticamente; este botón es solo para adelantar períodos.
                    </p>

                </form>
            </div>

            <div class="p-5">
                <h3 class="font-semibold text-gray-900 mb-2">Pendientes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-500">
                            <tr>
                                <th class="py-2">Periodo</th>
                                <th>Vence</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th class="text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pendientes as $c)
                                <tr>
                                    <td class="py-2">{{ $c->periodo_inicio->format('d/m/Y') }} -
                                        {{ $c->periodo_fin->format('d/m/Y') }}</td>
                                    <td>{{ $c->fecha_vencimiento->format('d/m/Y') }}</td>
                                    <td>L. {{ number_format($c->total, 2) }}</td>
                                    <td><span
                                            class="px-2 py-1 rounded-full text-xs border bg-red-50 text-red-700 border-red-200">Pendiente</span>
                                    </td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('cobranza.cargos.anular', $c) }}">
                                            @csrf
                                            <button
                                                class="px-3 py-1 rounded-xl border hover:bg-gray-50">Anular</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 text-gray-400">No hay cargos pendientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h3 class="font-semibold text-gray-900 mt-6 mb-2">Pagados (últimos)</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-500">
                            <tr>
                                <th class="py-2">Periodo</th>
                                <th>Pagado</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pagados->take(10) as $c)
                                <tr>
                                    <td class="py-2">{{ $c->periodo_inicio->format('d/m/Y') }} -
                                        {{ $c->periodo_fin->format('d/m/Y') }}</td>
                                    <td>{{ $c->pagado_en?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td>L. {{ number_format($c->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-3 text-gray-400">Aún no hay pagos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
