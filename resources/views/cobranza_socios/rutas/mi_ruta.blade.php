<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $ruta->nombre }}</h1>
                <p class="text-sm text-gray-500">{{ $ruta->fecha_ruta->format('d/m/Y') }} · Mi ruta</p>
            </div>

            <a href="{{ route('cobranza.rutas.pdf', $ruta) }}"
                class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">
                📄 Descargar PDF
            </a>

            <a href="{{ route('cobranza.rutas.mis') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                Volver
            </a>
        </div>

        <div class="space-y-4">
            @foreach ($empresas as $e)
                @php
                    // ✅ VARIABLE CLAVE (AQUÍ ESTABA EL ERROR)
                    $yaPagadoHoy = ($e->pagos_hoy_count ?? 0) > 0;
                @endphp

                <div class="bg-white rounded-2xl border p-5">
                    {{-- ENCABEZADO --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-900">
                                {{ $e->pivot->orden }}. {{ $e->nombre_empresa }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $e->direccion }} · {{ $e->ciudad }} · {{ $e->barrio_colonia }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Mora: {{ $e->meses_mora }} meses · L. {{ number_format($e->valor_mora, 2) }}
                            </p>
                        </div>

                        <div class="flex gap-2 flex-wrap items-center">
                            @if ($e->latitud && $e->longitud)
                                <a target="_blank"
                                    href="https://www.google.com/maps?q={{ $e->latitud }},{{ $e->longitud }}"
                                    class="px-3 py-1 rounded-xl border hover:bg-gray-50 text-sm">
                                    Maps
                                </a>
                            @endif

                            <span class="px-3 py-1 rounded-full text-xs border bg-gray-50 text-gray-700">
                                {{ strtoupper(str_replace('_', ' ', $e->pivot->estado_visita)) }}
                            </span>
                        </div>
                    </div>

                    {{-- FORMULARIO ESTADO VISITA --}}
                    <form method="POST"
                        action="{{ !$yaPagadoHoy ? route('cobranza.rutas.check', [$ruta, $e]) : '#' }}"
                        class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
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

                    {{-- BLOQUE PAGO --}}
                    @if ($e->pivot->estado_visita === 'visitado' && !$yaPagadoHoy)
                        <div class="mt-4 bg-gray-50 border rounded-xl p-4">
                            <form method="POST" action="{{ route('cobranza.pagos.store') }}"
                                class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                @csrf

                                <input type="hidden" name="empresa_id" value="{{ $e->id }}">
                                <input type="hidden" name="fecha_pago" value="{{ now()->toDateString() }}">

                                <input type="number" step="0.01" name="monto" class="rounded-xl border-gray-300"
                                    placeholder="Monto cobrado" required>

                                <select name="metodo" class="rounded-xl border-gray-300">
                                    @foreach (['efectivo', 'transferencia', 'deposito', 'cheque', 'otro'] as $m)
                                        <option value="{{ $m }}">{{ strtoupper($m) }}</option>
                                    @endforeach
                                </select>

                                <input name="referencia" class="rounded-xl border-gray-300"
                                    placeholder="Referencia (opcional)">

                                <div class="md:col-span-2 flex justify-end">
                                    <button class="px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700">
                                        Registrar pago
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- BLOQUE PAGADO (SOLO LECTURA) --}}
                    @if ($yaPagadoHoy)
                        <div
                            class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between">
                            <div class="text-green-800 font-semibold">
                                ✔ Pago registrado hoy
                            </div>
                            <span
                                class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 border border-green-200">
                                PAGADO
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
