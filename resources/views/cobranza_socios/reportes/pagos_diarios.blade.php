<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Histórico de pagos</h1>
            <p class="text-sm text-gray-500">
                Registro diario de pagos realizados por cobranza
            </p>
        </div>

        {{-- FILTROS --}}
        <form method="GET" class="bg-white border rounded-2xl p-4 grid grid-cols-1 md:grid-cols-6 gap-4">
            <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-xl border-gray-300">
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-xl border-gray-300">

            <select name="empresa_id" class="rounded-xl border-gray-300">
                <option value="">Todas las empresas</option>
                @foreach ($empresas as $e)
                    <option value="{{ $e->id }}" @selected(request('empresa_id') == $e->id)>
                        {{ $e->nombre_empresa }}
                    </option>
                @endforeach
            </select>

            <select name="usuario_id" class="rounded-xl border-gray-300">
                <option value="">Todos los gestores</option>
                @foreach ($usuarios as $u)
                    <option value="{{ $u->id }}" @selected(request('usuario_id') == $u->id)>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>

            <select name="metodo" class="rounded-xl border-gray-300">
                <option value="">Todos los métodos</option>
                @foreach (['efectivo', 'transferencia', 'deposito', 'cheque', 'otro'] as $m)
                    <option value="{{ $m }}" @selected(request('metodo') == $m)>
                        {{ strtoupper($m) }}
                    </option>
                @endforeach
            </select>

            <button class="rounded-xl bg-blue-600 text-white px-4 hover:bg-blue-700">
                Filtrar
            </button>
        </form>

        {{-- RESUMEN --}}
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
            <p class="text-sm text-green-700">Total recaudado</p>
            <p class="text-2xl font-bold text-green-900">
                L. {{ number_format($totalMonto, 2) }}
            </p>
        </div>

        {{-- TABLA --}}
        <div class="bg-white border rounded-2xl overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Empresa</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Gestor</th>
                        <th>Fecha / Hora</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pagos as $p)
                        <tr>
                            <td class="p-3 font-semibold">
                                {{ $p->empresa->nombre_empresa }}
                            </td>
                            <td>L. {{ number_format($p->monto, 2) }}</td>
                            <td>{{ strtoupper($p->metodo) }}</td>
                            <td>{{ $p->usuario?->name ?? '—' }}</td>
                            <td>{{ $p->fecha_pago->format('d/m/Y H:i') }}</td>
                            <td>{{ $p->referencia ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-400">
                                No hay pagos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pagos->links() }}

    </div>
</x-app-layout>
