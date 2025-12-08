<x-app-layout>
    @role('admin_ti')
        <div class="flex justify-end mb-4">
            <a href="{{ route('bitacora.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                + Registrar Actividad
            </a>
        </div>
    @endrole
    <h2 class="text-2xl font-bold mb-6">Bitácora de Actividades</h2>

    <div class="mb-6 bg-white p-4 shadow rounded-lg">
        <form method="GET" class="grid grid-cols-5 gap-4">


            <div>
                <label>Prioridad</label>
                <select name="prioridad" class="form-input w-full">
                    <option value="todas">Todas</option>
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                    <option value="critica">Crítica</option>
                </select>
            </div>

            <div>
                <label>Desde</label>
                <input type="date" name="desde" class="form-input w-full">
            </div>

            <div>
                <label>Hasta</label>
                <input type="date" name="hasta" class="form-input w-full">
            </div>

            <div class="flex items-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filtrar
                </button>
            </div>

        </form>
    </div>

    <div class="space-y-4">

        @foreach ($actividades as $a)
            <div
                class="bg-white p-4 shadow rounded-lg border-l-8 
    @if ($a->prioridad == 'alta') border-red-500 
    @elseif($a->prioridad == 'critica') border-red-700
    @elseif($a->prioridad == 'media') border-yellow-500
    @else border-green-500 @endif">

                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold">{{ $a->titulo }}</h3>
                    <span class="text-sm text-gray-500">
                        {{ $a->fecha->format('d/m/Y') }} —
                        {{ $a->hora_inicio }} {{ $a->hora_fin ? " - $a->hora_fin" : '' }}
                    </span>
                </div>

                <p class="text-gray-700 mb-2">{{ $a->descripcion }}</p>

                <p><strong>Equipo:</strong> {{ $a->equipo_afectado }}</p>
                <p><strong>Ubicación:</strong> {{ $a->ubicacion }}</p>
                <p><strong>Estado:</strong> {{ ucfirst($a->estado) }}</p>

                @if ($a->solucion_aplicada)
                    <p class="text-green-700 mt-2">
                        <strong>Solución:</strong> {{ $a->solucion_aplicada }}
                    </p>
                @endif

                @if ($a->evidencia)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $a->evidencia) }}" class="h-32 rounded shadow">
                    </div>
                @endif

                {{-- BOTONES --}}
                <div class="mt-3 flex items-center gap-3">
                    <a href="{{ route('bitacora.show', $a->id) }}"
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                        Ver
                    </a>

                    @role('admin_ti')
                        <a href="{{ route('bitacora.edit', $a->id) }}"
                            class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition">
                            Editar
                        </a>
                    @endrole
                </div>

                <p class="text-sm text-gray-500 mt-2">
                    Registrado por: {{ $a->user->name }}
                </p>
            </div>
        @endforeach

    </div>

    <div class="mt-6">
        {{ $actividades->links() }}
    </div>
</x-app-layout>
