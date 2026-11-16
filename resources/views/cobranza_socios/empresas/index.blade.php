<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

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

        <form method="GET" class="bg-white rounded-2xl border p-4 grid grid-cols-1 md:grid-cols-7 gap-3">

            <input name="buscar" value="{{ request('buscar') }}" class="rounded-xl border-gray-300"
                placeholder="Empresa o RTN">

            {{-- <input name="ciudad" value="{{ request('ciudad') }}" class="rounded-xl border-gray-300"
                placeholder="Ciudad"> --}}

            <select name="estatus" class="rounded-xl border-gray-300">
                <option value="">Estatus</option>
                <option value="al_dia" @selected(request('estatus') === 'al_dia')>Al día</option>
                <option value="en_mora" @selected(request('estatus') === 'en_mora')>En mora</option>
            </select>

            <select name="corte_id" class="rounded-xl border-gray-300">
                <option value="">Corte</option>
                @foreach ($cortes as $corte)
                    <option value="{{ $corte->id }}" @selected(request('corte_id') == $corte->id)>
                        Día {{ $corte->dia_corte }}
                    </option>
                @endforeach
            </select>
            
            <select name="tipo_pago" class="rounded-xl border-gray-300">
                <option value="">Tipo de pago</option>
                @foreach (['mensual', 'bimensual', 'trimestral', 'semestral', 'anual'] as $tp)
                    <option value="{{ $tp }}" @selected(request('tipo_pago') === $tp)>
                        {{ ucfirst($tp) }}
                    </option>
                @endforeach
            </select>

            <select name="tipo_empresa_id" class="rounded-xl border-gray-300">
                <option value="">Tipo empresa</option>
                @foreach ($tiposEmpresa as $t)
                    <option value="{{ $t->id }}" @selected(request('tipo_empresa_id') == $t->id)>
                        {{ $t->nombre }}
                    </option>
                @endforeach
            </select>

            <select name="categoria_id" class="rounded-xl border-gray-300">
                <option value="">Categoría</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}" @selected(request('categoria_id') == $c->id)>
                        {{ $c->nombre }}
                    </option>
                @endforeach
            </select>

            <button class="rounded-xl bg-gray-900 text-white px-4 py-2 hover:bg-black">
                Filtrar
            </button>

            <a href="{{ route('cobranza.empresas.index') }}"
                class="rounded-xl border px-4 py-2 text-center hover:bg-gray-50">
                Limpiar
            </a>
        </form>


        <div class="bg-white rounded-2xl border overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Empresa</th>
                        <th class="px-4 py-3 text-left font-semibold">Ciudad</th>
                        <th class="px-4 py-3 text-center font-semibold">Corte</th>
                        <th class="px-4 py-3 text-left font-semibold">Tipo</th>
                        <th class="px-4 py-3 text-left font-semibold">Próx. Cobro</th>
                        <th class="px-4 py-3 text-right font-semibold">Mora</th>
                        <th class="px-4 py-3 text-center font-semibold">Estatus</th>
                        @role('admin_ti|gerencia')
                            <th class="px-4 py-3 text-left font-semibold">Categoría</th>
                        @endrole
                        <th class="px-4 py-3 text-center font-semibold">Acción</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($empresas as $e)
                        <tr class="hover:bg-blue-50 transition duration-150">

                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $e->nombre_empresa }}</div>
                                <div class="text-xs text-gray-500 mt-1">RTN: {{ $e->rtn_empresa }}</div>
                            </td>

                            <td class="px-4 py-3 text-gray-700">{{ $e->ciudad ?? '—' }}</td>

                            <td class="px-4 py-3 text-center text-gray-700">
                                {{ $e->corte?->dia_corte ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">{{ strtoupper($e->tipo_pago) }}</td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $e->proxima_fecha_cobro?->format('d/m/Y') ?? '—' }}
                            </td>

                            @php
                                $moraReal = $e->cargos->where('estado', 'pendiente')->sum('total');
                            @endphp
                            <td class="px-4 py-3 text-right">
                                <span class="font-semibold {{ $moraReal > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    L. {{ number_format($moraReal, 2) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                @php
                                    $enMora = $e->cargos->where('estado', 'pendiente')->count() > 0;
                                @endphp
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $enMora ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $enMora ? '⚠️ EN MORA' : '✓ AL DÍA' }}
                                </span>
                            </td>

                            @role('admin_ti|gerencia')
                                <td class="px-4 py-3 text-gray-700">{{ $e->categoria?->nombre ?? '—' }}</td>
                            @endrole

                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('cobranza.empresas.show', $e) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-medium transition">
                                    👁️ Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center">
                                <div class="text-gray-400 text-sm">
                                    <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    No se encontraron empresas con los filtros aplicados.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $empresas->links() }}
        </div>

    </div>
</x-app-layout>
