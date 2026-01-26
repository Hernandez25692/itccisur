<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-8">

            {{-- ================= HEADER EJECUTIVO ================= --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 to-blue-800 p-8 shadow-xl">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,#fbbf24,transparent)]">
                </div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">
                            Reporte Diario de Pagos
                        </h1>
                        <p class="text-blue-200 mt-1">
                            Control de cobranza · Histórico operativo y financiero
                        </p>
                        <p class="text-xs text-blue-300 mt-2">
                            Actualizado: {{ now()->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('cobranza.empresas.index') }}"
                            class="px-4 py-2 rounded-xl bg-white/10 text-white border border-white/20 hover:bg-white/20 transition">
                            Empresas
                        </a>
                        <a href="{{ route('cobranza.dashboard') }}"
                            class="px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600 transition">
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- ================= FILTROS ================= --}}
            <div class="bg-white rounded-2xl border shadow-sm p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Desde</label>
                        <input type="date" name="desde" value="{{ request('desde') }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Hasta</label>
                        <input type="date" name="hasta" value="{{ request('hasta') }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Empresa</label>
                        <input name="empresa" value="{{ request('empresa') }}" class="w-full rounded-xl border-gray-300"
                            placeholder="Nombre o RTN">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gestor</label>
                        <select name="gestor" class="w-full rounded-xl border-gray-300">
                            <option value="">Todos</option>
                            @foreach ($gestores as $g)
                                <option value="{{ $g->id }}" @selected(request('gestor') == $g->id)>
                                    {{ $g->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button class="flex-1 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
                            Filtrar
                        </button>
                        <a href="{{ route('cobranza.reportes.pagos') }}"
                            class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- ================= KPIs ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl border p-5">
                    <p class="text-sm text-gray-500">Total pagos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPagos) }}</p>
                </div>

                <div class="bg-white rounded-2xl border p-5">
                    <p class="text-sm text-gray-500">Monto total recaudado</p>
                    <p class="text-3xl font-bold text-green-700">
                        L. {{ number_format($montoTotal, 2) }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl border p-5">
                    <p class="text-sm text-gray-500">Pagos hoy</p>
                    <p class="text-3xl font-bold text-blue-700">
                        {{ number_format($pagosHoy) }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl border p-5">
                    <p class="text-sm text-gray-500">Recaudado hoy</p>
                    <p class="text-3xl font-bold text-amber-700">
                        L. {{ number_format($montoHoy, 2) }}
                    </p>
                </div>
            </div>

            {{-- ================= TABLA DETALLADA ================= --}}
            <div class="bg-white rounded-2xl border overflow-hidden shadow-sm">
                <div class="p-5 border-b flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Pagos registrados</h2>
                    <span class="text-sm text-gray-500">
                        {{ $pagos->total() }} registros
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left">Fecha</th>
                                <th class="px-4 py-3 text-left">Empresa</th>
                                <th class="px-4 py-3 text-left">Gestor</th>
                                <th class="px-4 py-3 text-left">Método</th>
                                <th class="px-4 py-3 text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pagos as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ $p->fecha_pago->format('d/m/Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ $p->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <p class="font-semibold text-gray-900">
                                            {{ $p->empresa->nombre_empresa }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            RTN: {{ $p->empresa->rtn_empresa }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $p->gestor?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 border">
                                            {{ strtoupper($p->metodo) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right font-bold text-green-700">
                                        L. {{ number_format($p->monto, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                        No hay pagos registrados para este período.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="p-4 border-t">
                    {{ $pagos->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
