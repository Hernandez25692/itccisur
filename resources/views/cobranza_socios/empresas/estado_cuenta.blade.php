<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-blue-900">Estado de Cuenta</h1>
                <p class="text-blue-600 mt-1">{{ $empresa->nombre_empresa }}</p>
            </div>

            <a href="{{ route('cobranza.empresas.estado_cuenta_pdf', $empresa) }}"
                class="px-6 py-2 rounded-lg bg-yellow-500 text-blue-900 font-semibold hover:bg-yellow-600 transition">
                📥 Descargar PDF
            </a>
        </div>

        <div class="bg-white rounded-xl border-2 border-blue-200 overflow-hidden shadow-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
                    <tr>
                        <th class="p-4 text-left">Tipo Escritura</th>
                        <th class="p-4 text-left">Nombre Social</th>
                        <th class="p-4 text-center">Días</th>
                        <th class="p-4 text-center">Año</th>
                        <th class="p-4 text-center">Mes</th>
                        <th class="p-4 text-right">Adeuda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @foreach ($filas as $f)
                        <tr class="hover:bg-blue-50 transition {{ $f['estado'] === 'pendiente' ? 'bg-red-50' : 'bg-green-50' }}">
                            <td class="p-4 text-blue-900">{{ $empresa->tipoEmpresa->nombre ?? '—' }}</td>

                            <td class="p-4 font-semibold text-blue-900">
                                {{ $empresa->nombre_empresa }}
                            </td>

                            <td class="p-4 text-center text-blue-700">
                                {{ $f['periodo_texto'] }}
                            </td>

                            <td class="p-4 text-center text-blue-700">
                                {{ $f['anio'] }}
                            </td>

                            <td class="p-4 text-center text-blue-700">
                                {{ str_pad($f['mes'], 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="p-4 text-right font-semibold {{ $f['estado'] === 'pendiente' ? 'text-red-700' : 'text-green-700' }}">
                                L. {{ number_format($f['monto'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="bg-blue-900 text-white font-bold">
                    <tr>
                        <td colspan="5" class="p-4 text-right">TOTAL</td>
                        <td class="p-4 text-right text-yellow-400">
                            L. {{ number_format($totalAdeuda, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <a href="{{ route('cobranza.empresas.show', $empresa) }}" class="inline-flex items-center text-blue-900 font-semibold hover:text-yellow-500 transition">
            ← Volver a la empresa
        </a>

    </div>
</x-app-layout>
