<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Registrar pago</h1>
                <p class="text-sm text-gray-500">
                    {{ $empresa->nombre_empresa }} · RTN {{ $empresa->rtn_empresa }}
                </p>
            </div>

            <a href="{{ route('cobranza.empresas.show', $empresa) }}"
                class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                Volver
            </a>
        </div>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cobranza.pagos.store') }}"
            class="bg-white rounded-2xl border p-6 space-y-6" id="formPago">
            @csrf

            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
            <input type="hidden" name="monto" id="monto_total">

            {{-- DATOS GENERALES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Fecha pago</label>
                    <input type="date" name="fecha_pago" value="{{ old('fecha_pago', now()->toDateString()) }}"
                        class="w-full rounded-xl border-gray-300" required>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Método</label>
                    <select name="metodo" class="w-full rounded-xl border-gray-300">
                        @foreach (['efectivo', 'transferencia', 'deposito', 'cheque', 'otro'] as $m)
                            <option value="{{ $m }}" @selected(old('metodo', 'efectivo') === $m)>
                                {{ strtoupper($m) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Monto total</label>
                    <input type="text" id="monto_visual"
                        class="w-full rounded-xl border-gray-300 bg-gray-100 font-semibold" value="L. 0.00" readonly>
                </div>
            </div>

            {{-- REFERENCIA Y COMENTARIO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Referencia (opcional)</label>
                    <input name="referencia" value="{{ old('referencia') }}" class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Comentario</label>
                    <input name="comentario" value="{{ old('comentario') }}" class="w-full rounded-xl border-gray-300">
                </div>
            </div>

            {{-- CARGOS PENDIENTES --}}
            <div class="bg-gray-50 border rounded-2xl p-4">
                <p class="font-semibold text-gray-900">Cargos pendientes</p>
                <p class="text-sm text-gray-500">
                    Seleccione las cuotas a pagar. Por defecto están marcadas las más antiguas.
                </p>

                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-500">
                            <tr>
                                <th></th>
                                <th>Periodo</th>
                                <th>Vence</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($empresa->cargos as $c)
                                <tr>
                                    <td class="py-2">
                                        <input type="checkbox" class="cargo-check rounded border-gray-300"
                                            data-total="{{ $c->total }}" checked>
                                    </td>
                                    <td>
                                        {{ $c->periodo_inicio->format('d/m/Y') }} -
                                        {{ $c->periodo_fin->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $c->fecha_vencimiento->format('d/m/Y') }}</td>
                                    <td class="text-right font-semibold">
                                        L. {{ number_format($c->total, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-3 text-gray-400">
                                        No hay cargos pendientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACCIONES --}}
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('cobranza.empresas.show', $empresa) }}"
                    class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Cancelar
                </a>
                <button class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    Registrar pago
                </button>
            </div>
        </form>
    </div>

    {{-- SCRIPT --}}
    <script>
        function recalcularMonto() {
            let total = 0;
            document.querySelectorAll('.cargo-check:checked').forEach(el => {
                total += parseFloat(el.dataset.total);
            });

            document.getElementById('monto_total').value = total.toFixed(2);
            document.getElementById('monto_visual').value =
                'L. ' + total.toLocaleString('es-HN', {
                    minimumFractionDigits: 2
                });
        }

        document.querySelectorAll('.cargo-check').forEach(el => {
            el.addEventListener('change', recalcularMonto);
        });

        recalcularMonto();
    </script>
</x-app-layout>
