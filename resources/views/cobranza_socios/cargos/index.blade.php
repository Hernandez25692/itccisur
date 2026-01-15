<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Cargos (Estado de cuenta)</h1>
            <a href="{{ route('cobranza.empresas.index') }}"
                class="px-4 py-2 rounded-xl border hover:bg-gray-50">Empresas</a>
        </div>

        <form class="bg-white rounded-2xl border p-4 grid grid-cols-1 md:grid-cols-4 gap-3" method="GET">
            <input name="buscar" value="{{ request('buscar') }}" class="rounded-xl border-gray-300"
                placeholder="Empresa o RTN">
            <select name="estado" class="rounded-xl border-gray-300">
                <option value="">Estado (todos)</option>
                @foreach (['pendiente', 'pagado', 'anulado'] as $e)
                    <option value="{{ $e }}" @selected(request('estado') === $e)>{{ strtoupper($e) }}</option>
                @endforeach
            </select>
            <div></div>
            <button class="rounded-xl bg-gray-900 text-white px-4 py-2 hover:bg-black">Filtrar</button>
        </form>

        <div class="bg-white rounded-2xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 bg-gray-50">
                        <tr>
                            <th class="py-3 px-4">Empresa</th>
                            <th class="px-4">Periodo</th>
                            <th class="px-4">Vence</th>
                            <th class="px-4">Total</th>
                            <th class="px-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($cargos as $c)
                            <tr>
                                <td class="py-3 px-4">
                                    <a class="font-semibold text-blue-600 hover:underline"
                                        href="{{ route('cobranza.empresas.show', $c->empresa) }}">
                                        {{ $c->empresa->nombre_empresa }}
                                    </a>
                                    <div class="text-xs text-gray-500">RTN: {{ $c->empresa->rtn_empresa }}</div>
                                </td>
                                <td class="px-4">{{ $c->periodo_inicio->format('d/m/Y') }} -
                                    {{ $c->periodo_fin->format('d/m/Y') }}</td>
                                <td class="px-4">{{ $c->fecha_vencimiento->format('d/m/Y') }}</td>
                                <td class="px-4">L. {{ number_format($c->total, 2) }}</td>
                                <td class="px-4">{{ strtoupper($c->estado) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{ $cargos->links() }}
    </div>
</x-app-layout>
