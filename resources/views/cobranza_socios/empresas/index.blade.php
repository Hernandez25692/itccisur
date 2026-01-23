<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Empresas · Cobranza</h1>

            </div>

            <div class="flex flex-wrap gap-2">
                @role('admin_ti|cobranza')
                    <a href="{{ route('cobranza.empresas.create') }}"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium transition">
                        ➕ Nueva Empresa
                    </a>
                @endrole

                <a href="{{ route('cobranza.dashboard') }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium transition">
                    📊 Dashboard
                </a>

                <a href="{{ route('cobranza.rutas.index') }}"
                    class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition">
                    🗺️ Rutas
                </a>

                @role('admin_ti|cobranza')
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium transition flex items-center gap-2">
                            ⚙️ Configuración
                            <svg class="w-4 h-4 transition" :class="open && 'rotate-180'" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open=false"
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-50 divide-y">
                            <a href="{{ route('cobranza.categorias.index') }}"
                                class="block px-4 py-3 hover:bg-gray-50 text-sm text-gray-700 font-medium transition">
                                📁 Categorías de Empresa
                            </a>
                            <a href="{{ route('cobranza.tipos-empresa.index') }}"
                                class="block px-4 py-3 hover:bg-gray-50 text-sm text-gray-700 font-medium transition">
                                🏷️ Tipos de Empresa
                            </a>
                            <a href="{{ route('cobranza.capital-rangos.index') }}"
                                class="block px-4 py-3 hover:bg-gray-50 text-sm text-gray-700 font-medium transition">
                                💰 Rangos de Capital / Cuotas
                            </a>
                        </div>
                    </div>
                @endrole
            </div>
        </div>

        {{-- ================= MENSAJES ================= --}}
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

        {{-- ================= FILTROS ================= --}}
        <form method="GET" class="bg-white rounded-2xl border p-4 grid grid-cols-1 md:grid-cols-5 gap-3">

            <input name="buscar" value="{{ request('buscar') }}" class="rounded-xl border-gray-300"
                placeholder="Empresa o RTN">

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

            <a href="{{ route('cobranza.empresas.index') }}"
                class="rounded-xl border px-4 py-2 text-center hover:bg-gray-50">
                Limpiar
            </a>
        </form>

        {{-- ================= TABLA RMS ================= --}}
        <div class="bg-white rounded-2xl border overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-2 text-left">Empresa</th>
                        <th>Ciudad</th>
                        <th class="text-center">Corte</th>
                        <th>Tipo</th>
                        <th>Próx. cobro</th>
                        <th class="text-right">Mora</th>
                        <th>Estatus</th>
                        @role('admin_ti|gerencia')
                            <th>Categoría</th>
                        @endrole
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($empresas as $e)
                        <tr class="hover:bg-gray-50">

                            {{-- Empresa --}}
                            <td class="px-3 py-2 font-semibold text-gray-900">
                                {{ $e->nombre_empresa }}
                                <div class="text-xs text-gray-500">
                                    RTN: {{ $e->rtn_empresa }}
                                </div>
                            </td>

                            {{-- Ciudad --}}
                            <td>{{ $e->ciudad ?? '—' }}</td>

                            {{-- Corte --}}
                            <td class="text-center">
                                {{ $e->corte?->dia_corte ?? '-' }}
                            </td>

                            {{-- Tipo --}}
                            <td>{{ strtoupper($e->tipo_pago) }}</td>

                            {{-- Próximo cobro --}}
                            <td>
                                {{ $e->proxima_fecha_cobro?->format('d/m/Y') ?? '—' }}
                            </td>

                            {{-- Mora --}}
                            @php
                                $moraReal = $e->cargos->where('estado', 'pendiente')->sum('total');
                            @endphp

                            <td class="text-right font-semibold">
                                L. {{ number_format($moraReal, 2) }}
                            </td>


                            {{-- Estatus --}}
                            <td>
                                @php
                                    $enMora = $e->cargos->where('estado', 'pendiente')->count() > 0;
                                @endphp

                                <span
                                    class="px-2 py-1 text-xs rounded-full border
    {{ $enMora ? 'bg-red-50 text-red-700 border-red-200' : 'bg-green-50 text-green-700 border-green-200' }}">
                                    {{ $enMora ? 'EN MORA' : 'AL DÍA' }}
                                </span>

                            </td>

                            {{-- Categoría (solo admin) --}}
                            @role('admin_ti|gerencia')
                                <td>{{ $e->categoria?->nombre ?? '—' }}</td>
                            @endrole

                            {{-- Acción --}}
                            <td class="text-center">
                                <a href="{{ route('cobranza.empresas.show', $e) }}"
                                    class="px-3 py-1 rounded-lg border hover:bg-gray-100 text-xs">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-400">
                                No se encontraron empresas con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= PAGINACIÓN ================= --}}
        <div>
            {{ $empresas->links() }}
        </div>

    </div>
</x-app-layout>
