<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Estado de Cuenta</h1>
                <p class="text-gray-600">{{ $empresa->nombre_empresa }}</p>
            </div>

            <a href="{{ route('cobranza.empresas.estado_cuenta_pdf', $empresa) }}"
                class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                Descargar PDF
            </a>
        </div>

        <div class="bg-white rounded-2xl border overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Tipo Escritura</th>
                        <th class="p-3 text-left">Nombre Social</th>
                        <th class="p-3 text-center">Días</th>
                        <th class="p-3 text-center">Año</th>
                        <th class="p-3 text-center">Mes</th>
                        <th class="p-3 text-right">Adeuda</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($filas as $f)
                        <tr class="{{ $f['estado'] === 'pendiente' ? 'bg-red-50' : 'bg-green-50' }}">
                            <td>{{ $empresa->tipoEmpresa->nombre ?? '—' }}</td>

                            <td class="font-semibold">
                                {{ $empresa->nombre_empresa }}
                            </td>

                            <td class="text-sm text-gray-600">
                                {{ $f['periodo_texto'] }}
                            </td>

                            <td class="text-center">
                                {{ $f['anio'] }}
                            </td>

                            <td class="text-center">
                                {{ str_pad($f['mes'], 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td
                                class="text-right font-semibold
            {{ $f['estado'] === 'pendiente' ? 'text-red-700' : 'text-green-700' }}">
                                L. {{ number_format($f['monto'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="bg-gray-50 font-bold">
                    <tr>
                        <td colspan="5" class="p-3 text-right">TOTAL</td>
                        <td class="p-3 text-right">
                            L. {{ number_format($totalAdeuda, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <a href="{{ route('cobranza.empresas.show', $empresa) }}" class="text-indigo-600 hover:underline">
            ← Volver a la empresa
        </a>

    </div>
</x-app-layout>
