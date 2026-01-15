<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Empresas - Cobranza</h1>
                <p class="text-sm text-gray-500">
                    Control exacto por corte fijo + cargos (estado de cuenta).
                </p>
            </div>

            <div class="flex flex-wrap gap-2 items-center">

                {{-- DASHBOARD --}}
                <a href="{{ route('cobranza.dashboard') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Dashboard
                </a>

                {{-- NUEVA EMPRESA --}}
                <a href="{{ route('cobranza.empresas.create') }}"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    + Nueva Empresa
                </a>
                <a href="{{ route('cobranza.rutas.index') }}"
                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                    Rutas de Cobranza
                </a>

                {{-- CONFIGURACIÓN / CATÁLOGOS --}}
                @role('admin_ti|cobranza')
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="px-4 py-2 rounded-xl border hover:bg-gray-50 flex items-center gap-1">
                            Configuración
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open=false"
                            class="absolute right-0 mt-2 w-64 bg-white border rounded-xl shadow-lg z-50 overflow-hidden">

                            <a href="{{ route('cobranza.categorias.index') }}"
                                class="block px-4 py-3 hover:bg-gray-50 text-sm">
                                Categorías de Empresa
                            </a>

                            <a href="{{ route('cobranza.tipos-empresa.index') }}"
                                class="block px-4 py-3 hover:bg-gray-50 text-sm">
                                Tipos de Empresa
                            </a>

                            <a href="{{ route('cobranza.capital-rangos.index') }}"
                                class="block px-4 py-3 hover:bg-gray-50 text-sm">
                                Rangos de Capital / Cuotas
                            </a>
                        </div>
                    </div>
                @endrole

            </div>
        </div>

        {{-- MENSAJES --}}
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

        {{-- FILTROS --}}
        <form class="bg-white rounded-2xl border p-4 grid grid-cols-1 md:grid-cols-4 gap-3" method="GET">
            <input name="buscar" value="{{ request('buscar') }}" class="rounded-xl border-gray-300"
                placeholder="Buscar por empresa o RTN">

            <input name="ciudad" value="{{ request('ciudad') }}" class="rounded-xl border-gray-300"
                placeholder="Ciudad">

            <select name="estatus" class="rounded-xl border-gray-300">
                <option value="">Estatus (todos)</option>
                <option value="al_dia" @selected(request('estatus') === 'al_dia')>Al día</option>
                <option value="en_mora" @selected(request('estatus') === 'en_mora')>En mora</option>
            </select>

            <button class="rounded-xl bg-gray-900 text-white px-4 py-2 hover:bg-black">
                Filtrar
            </button>
        </form>

        {{-- LISTADO EMPRESAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($empresas as $e)
                <a href="{{ route('cobranza.empresas.show', $e) }}"
                    class="bg-white rounded-2xl border p-5 hover:shadow-md transition">

                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-900">{{ $e->nombre_empresa }}</p>
                            <p class="text-sm text-gray-500">RTN: {{ $e->rtn_empresa }}</p>
                            <p class="text-sm text-gray-500">
                                Corte: {{ $e->corte?->dia_corte ?? '-' }} |
                                {{ strtoupper($e->tipo_pago) }}
                            </p>
                        </div>

                        @php
                            $badge =
                                $e->estatus_cobranza === 'en_mora'
                                    ? 'bg-red-50 text-red-700 border-red-200'
                                    : 'bg-green-50 text-green-700 border-green-200';
                        @endphp

                        <span class="text-xs px-3 py-1 rounded-full border {{ $badge }}">
                            {{ $e->estatus_cobranza === 'en_mora' ? 'EN MORA' : 'AL DÍA' }}
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Próximo cobro</p>
                            <p class="font-semibold text-gray-900">
                                {{ $e->proxima_fecha_cobro?->format('d/m/Y') ?? '—' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Mora</p>
                            <p class="font-bold text-gray-900">
                                L. {{ number_format($e->valor_mora, 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-gray-500">
                        {{ $e->ciudad ?? '—' }} · {{ $e->barrio_colonia ?? '—' }}
                    </div>

                    @if ($e->observacion_cobro === 'incobrable')
                        <div
                            class="mt-3 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                            ⚠ Incobrable (≥ 18 meses)
                        </div>
                    @endif

                </a>
            @endforeach
        </div>

        {{-- PAGINACIÓN --}}
        <div>
            {{ $empresas->links() }}
        </div>

    </div>
</x-app-layout>
