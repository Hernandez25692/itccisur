<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $ruta->nombre }}</h1>
                <p class="text-sm text-gray-500">{{ $ruta->fecha_ruta->format('d/m/Y') }} · Mi ruta</p>
            </div>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('cobranza.rutas.mis') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Volver
                </a>
            </div>
        </div>

        <div class="space-y-4">
            @foreach ($empresas as $e)
                <div class="bg-white rounded-2xl border p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-900">{{ $e->pivot->orden }}. {{ $e->nombre_empresa }}</p>
                            <p class="text-sm text-gray-500">{{ $e->direccion }} · {{ $e->ciudad }} ·
                                {{ $e->barrio_colonia }}</p>
                            <p class="text-xs text-gray-500">Mora: {{ $e->meses_mora }} meses · L.
                                {{ number_format($e->valor_mora, 2) }}</p>
                        </div>

                        <div class="flex gap-2 flex-wrap">
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

                    <form method="POST" action="{{ route('cobranza.rutas.check', [$ruta, $e]) }}"
                        class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf
                        <select name="estado_visita" class="rounded-xl border-gray-300">
                            @foreach (['pendiente', 'visitado', 'no_encontrado', 'reprogramado'] as $st)
                                <option value="{{ $st }}" @selected($e->pivot->estado_visita === $st)>
                                    {{ strtoupper(str_replace('_', ' ', $st)) }}
                                </option>
                            @endforeach
                        </select>

                        <input name="nota_visita" value="{{ $e->pivot->nota_visita }}"
                            class="rounded-xl border-gray-300" placeholder="Nota de visita">

                        <button class="rounded-xl bg-blue-600 text-white hover:bg-blue-700 px-4 py-2">Guardar</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
