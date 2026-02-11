<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $ruta->nombre }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $ruta->fecha_ruta->format('d/m/Y') }} · Mi ruta
                </p>
            </div>
            @role('admin_ti|gerencia|cobranza')
                <a href="{{ route('cobranza.rutas.pdf', $ruta) }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 flex items-center gap-2">
                    📄 Descargar ruta
                </a>
            @endrole

            <a href="{{ route('cobranza.rutas.mis') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                Volver
            </a>
        </div>

        {{-- EMPRESAS --}}
        <div class="space-y-4">
            @foreach ($empresas as $e)
                @php
                    $yaPagadoHoy = ($e->pagos_hoy_count ?? 0) > 0;
                @endphp

                <div class="bg-white rounded-2xl border p-5 space-y-4">

                    {{-- ENCABEZADO --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-900">
                                {{ $e->pivot->orden }}. {{ $e->nombre_empresa }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $e->direccion }} · {{ $e->ciudad }} · {{ $e->barrio_colonia }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs border bg-gray-50 text-gray-700">
                            {{ strtoupper(str_replace('_', ' ', $e->pivot->estado_visita)) }}
                        </span>
                    </div>

                    {{-- FORM ESTADO VISITA --}}
                    <form method="POST"
                        action="{{ !$yaPagadoHoy ? route('cobranza.rutas.check', [$ruta, $e]) : '#' }}"
                        class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf

                        <select name="estado_visita" class="rounded-xl border-gray-300" @disabled($yaPagadoHoy)>
                            @foreach (['pendiente', 'visitado', 'no_encontrado', 'reprogramado'] as $st)
                                <option value="{{ $st }}" @selected($e->pivot->estado_visita === $st)>
                                    {{ strtoupper(str_replace('_', ' ', $st)) }}
                                </option>
                            @endforeach
                        </select>

                        <input name="nota_visita" value="{{ $e->pivot->nota_visita }}"
                            class="rounded-xl border-gray-300" placeholder="Nota de visita" @readonly($yaPagadoHoy)>

                        <button
                            class="rounded-xl px-4 py-2
                            {{ $yaPagadoHoy ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                            @disabled($yaPagadoHoy)>
                            Guardar
                        </button>
                    </form>

                    {{-- BLOQUE PAGO (SOLO SI VISITADO) --}}
                    @if ($e->pivot->estado_visita === 'visitado' && !$yaPagadoHoy)
                        <form method="POST" action="{{ route('cobranza.pagos.store') }}"
                            class="bg-gray-50 border rounded-xl p-4 space-y-4 pago-form"
                            data-empresa="{{ $e->id }}">
                            @csrf

                            <input type="hidden" name="empresa_id" value="{{ $e->id }}">
                            <input type="hidden" name="fecha_pago" value="{{ now()->toDateString() }}">

                            <p class="font-semibold text-gray-900">
                                Estado de cuenta – seleccione cuotas a pagar
                            </p>

                            {{-- CARGOS --}}
                            <table class="w-full text-sm">
                                <thead class="text-gray-500">
                                    <tr>
                                        <th></th>
                                        <th>Periodo</th>
                                        <th>Vence</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($e->cargos as $c)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="cargos[]" value="{{ $c->id }}"
                                                    data-monto="{{ $c->total }}"
                                                    class="cargo-check rounded border-gray-300" checked>
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
                                            <td colspan="4" class="text-gray-400 py-3">
                                                No hay cargos pendientes.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- TOTAL DINÁMICO --}}
                            <div class="flex justify-between items-center border-t pt-3">
                                <span class="font-semibold text-gray-700">
                                    Total a cobrar
                                </span>
                                <span class="text-xl font-bold text-gray-900">
                                    L. <span class="total-cobro">0.00</span>
                                </span>
                            </div>

                            {{-- MÉTODO --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <select name="metodo" class="rounded-xl border-gray-300">
                                    @foreach (['efectivo', 'transferencia', 'deposito', 'cheque', 'otro'] as $m)
                                        <option value="{{ $m }}">{{ strtoupper($m) }}</option>
                                    @endforeach
                                </select>

                                <input name="referencia" class="rounded-xl border-gray-300 md:col-span-2"
                                    placeholder="Referencia (opcional)">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700">
                                    Registrar pago
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- YA PAGADO --}}
                    @if ($yaPagadoHoy)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <span class="text-green-800 font-semibold">
                                ✔ Pago registrado hoy
                            </span>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    </div>

    {{-- SCRIPT TOTAL DINÁMICO --}}
    <script>
        document.querySelectorAll('.pago-form').forEach(form => {
            const checks = form.querySelectorAll('.cargo-check');
            const totalSpan = form.querySelector('.total-cobro');

            function recalcular() {
                let total = 0;
                checks.forEach(c => {
                    if (c.checked) {
                        total += parseFloat(c.dataset.monto);
                    }
                });
                totalSpan.textContent = total.toFixed(2);
            }

            checks.forEach(c => c.addEventListener('change', recalcular));
            recalcular();
        });
    </script>
</x-app-layout>
