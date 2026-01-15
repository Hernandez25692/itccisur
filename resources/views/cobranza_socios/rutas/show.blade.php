<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $ruta->nombre }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $ruta->fecha_ruta->format('d/m/Y') }}
                    · Estado: <span class="font-semibold">{{ strtoupper($ruta->estado) }}</span>
                    @if ($ruta->gestor_id)
                        · Gestor ID {{ $ruta->gestor_id }}
                    @endif
                </p>
            </div>

            <div class="flex gap-2 flex-wrap items-center">

                {{-- ✅ SOLO SI ES SUGERIDA: asignar gestores --}}
                @if ($ruta->estado === 'sugerida')
                    <form method="POST" action="{{ route('cobranza.rutas.asignar', $ruta) }}">
                        @csrf
                        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                            Asignar gestores automáticamente
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('cobranza.rutas.ordenar', $ruta) }}">
                    @csrf
                    <button class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                        Ordenar por cercanía
                    </button>
                </form>

                <a href="{{ route('cobranza.rutas.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Volver
                </a>
            </div>
        </div>

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

        <div class="space-y-4">
            @forelse ($ruta->empresas as $e)
                <div class="bg-white rounded-2xl border p-5">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-900">
                                {{ $e->pivot->orden }}. {{ $e->nombre_empresa }}
                            </p>

                            {{-- ✅ AQUÍ SE VE A QUIÉN LE TOCA --}}
                            <p class="text-sm text-gray-700 mt-1">
                                Gestor asignado:
                                <span class="font-semibold">
                                    {{ $e->pivot->gestor_nombre ?? '— SIN ASIGNAR —' }}
                                </span>
                            </p>
                            @role('admin_ti|gerencia')
                                <div class="mt-2">
                                    <label class="text-xs text-gray-500 block mb-1">Reasignar gestor (flash)</label>

                                    <form method="POST" action="{{ route('cobranza.rutas.reasignar_empresa', $ruta) }}">
                                        @csrf
                                        <input type="hidden" name="empresa_id" value="{{ $e->id }}">

                                        <select name="gestor_id" class="rounded-xl border-gray-300 text-sm w-full md:w-72"
                                            onchange="this.form.submit()">
                                            <option value="">— SIN ASIGNAR —</option>

                                            @foreach ($gestores as $g)
                                                <option value="{{ $g->id }}" @selected(($e->pivot->gestor_id ?? null) == $g->id)>
                                                    {{ $g->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            @endrole

                            <p class="text-sm text-gray-500">
                                {{ $e->direccion }} · {{ $e->ciudad }} · {{ $e->barrio_colonia }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Mora: {{ $e->meses_mora }} meses · L. {{ number_format($e->valor_mora, 2) }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 items-center">
                            @if ($e->latitud && $e->longitud)
                                <a target="_blank"
                                    href="https://www.google.com/maps?q={{ $e->latitud }},{{ $e->longitud }}"
                                    class="px-3 py-1 rounded-xl border hover:bg-gray-50 text-sm">
                                    Maps
                                </a>
                            @endif

                            <span
                                class="px-3 py-1 rounded-full text-xs border
                                @if ($e->pivot->estado_visita === 'pendiente') bg-gray-50 text-gray-700
                                @elseif($e->pivot->estado_visita === 'visitado') bg-green-50 text-green-700
                                @elseif($e->pivot->estado_visita === 'no_encontrado') bg-red-50 text-red-700
                                @else bg-amber-50 text-amber-700 @endif">
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

                        <button class="rounded-xl bg-blue-600 text-white hover:bg-blue-700 px-4 py-2">
                            Guardar
                        </button>
                    </form>

                </div>
            @empty
                <div class="p-6 rounded-2xl border bg-white text-gray-500">
                    Esta ruta no tiene empresas asignadas.
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
