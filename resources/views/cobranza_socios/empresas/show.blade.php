<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $empresa->nombre_empresa }}</h1>
                <p class="text-sm text-gray-500">RTN: {{ $empresa->rtn_empresa }} · Corte
                    {{ $empresa->corte?->dia_corte }} · {{ strtoupper($empresa->tipo_pago) }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('cobranza.empresas.edit', $empresa) }}"
                    class="px-4 py-2 rounded-xl border hover:bg-gray-50">Editar</a>
                <a href="{{ route('cobranza.pagos.create', $empresa) }}"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Registrar pago</a>
                <a href="{{ route('cobranza.empresas.index') }}"
                    class="px-4 py-2 rounded-xl border hover:bg-gray-50">Volver</a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Estatus</p>
                <p
                    class="text-xl font-bold {{ $empresa->estatus_cobranza === 'en_mora' ? 'text-red-700' : 'text-green-700' }}">
                    {{ $empresa->estatus_cobranza === 'en_mora' ? 'EN MORA' : 'AL DÍA' }}
                </p>
                <p class="text-sm text-gray-500 mt-2">Próximo cobro: <span
                        class="font-semibold text-gray-900">{{ $empresa->proxima_fecha_cobro?->format('d/m/Y') ?? '—' }}</span>
                </p>
            </div>
            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Mora</p>
                <p class="text-2xl font-bold text-gray-900">L. {{ number_format($empresa->valor_mora, 2) }}</p>
                <p class="text-sm text-gray-500 mt-2">Mora ({{ strtoupper($empresa->tipo_pago) }}):  <span
                        class="font-semibold text-gray-900">{{ $empresa->meses_mora }}</span></p>
            </div>
            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Observación</p>
                <p
                    class="text-xl font-bold {{ $empresa->observacion_cobro === 'incobrable' ? 'text-amber-700' : 'text-gray-900' }}">
                    {{ strtoupper($empresa->observacion_cobro) }}
                </p>
                <p class="text-sm text-gray-500 mt-2">Cuota aplicada: <span class="font-semibold text-gray-900">L.
                        {{ number_format($empresa->cuota_aplicada, 2) }}</span></p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-900 mb-4">Ubicación</h2>
            <p class="text-sm text-gray-700">{{ $empresa->direccion ?? '—' }}</p>
            <p class="text-sm text-gray-500">{{ $empresa->ciudad ?? '—' }} · {{ $empresa->barrio_colonia ?? '—' }}</p>

            @if ($empresa->latitud && $empresa->longitud)
                <div class="mt-3">
                    <a target="_blank" class="text-blue-600 hover:underline"
                        href="https://www.google.com/maps?q={{ $empresa->latitud }},{{ $empresa->longitud }}">
                        Abrir en Google Maps ({{ $empresa->latitud }}, {{ $empresa->longitud }})
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border p-6">
                <h2 class="font-bold text-gray-900 mb-3">Contactos</h2>

                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-gray-900">Teléfonos fijos</p>
                    <ul class="list-disc pl-5">
                        @forelse($empresa->telefonosFijos as $t)
                            <li>{{ $t->telefono }}</li>
                        @empty
                            <li class="text-gray-400">—</li>
                        @endforelse
                    </ul>

                    <p class="font-semibold text-gray-900 mt-3">Celulares</p>
                    <ul class="list-disc pl-5">
                        @forelse($empresa->celulares as $c)
                            <li>{{ $c->celular }}</li>
                        @empty
                            <li class="text-gray-400">—</li>
                        @endforelse
                    </ul>

                    <p class="font-semibold text-gray-900 mt-3">Correos</p>
                    <ul class="list-disc pl-5">
                        @forelse($empresa->correos as $e)
                            <li>{{ $e->correo }}</li>
                        @empty
                            <li class="text-gray-400">—</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h2 class="font-bold text-gray-900 mb-3">Propietarios</h2>
                <div class="space-y-2">
                    @forelse($empresa->propietarios as $p)
                        <div class="p-3 rounded-xl border">
                            <p class="font-semibold text-gray-900">{{ $p->nombre }}</p>
                            <p class="text-sm text-gray-500">Identidad: {{ $p->identidad }} @if ($p->rtn)
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
                                            <button class="px-3 py-1 rounded-xl border hover:bg-gray-50">Anular</button>
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
