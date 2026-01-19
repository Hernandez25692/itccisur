<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Empresas · Cobranza</h1>
                <p class="text-sm text-gray-500">
                    Panel operativo estilo RMS / Excel para control de cobros.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('cobranza.dashboard') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Dashboard
                </a>

                <a href="{{ route('cobranza.rutas.index') }}"
                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                    Rutas
                </a>

                @role('admin_ti|cobranza')
                    <a href="{{ route('cobranza.empresas.create') }}"
                        class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                        + Nueva Empresa
                    </a>
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
                            <td class="text-right font-semibold">
                                L. {{ number_format($e->valor_mora, 2) }}
                            </td>

                            {{-- Estatus --}}
                            <td>
                                <span
                                    class="px-2 py-1 text-xs rounded-full border
                                    {{ $e->estatus_cobranza === 'en_mora'
                                        ? 'bg-red-50 text-red-700 border-red-200'
                                        : 'bg-green-50 text-green-700 border-green-200' }}">
                                    {{ $e->estatus_cobranza === 'en_mora' ? 'EN MORA' : 'AL DÍA' }}
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
