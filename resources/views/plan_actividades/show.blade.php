<x-app-layout>

    <div class="max-w-5xl mx-auto p-6 bg-white shadow rounded">

        <h1 class="text-2xl font-bold mb-4">Actividad {{ $actividad->codigo }}</h1>

        <div class="mb-4">
            <strong>Sección:</strong> {{ $actividad->seccion }}
        </div>

        <div class="mb-4">
            <strong>Actividad:</strong> {{ $actividad->actividad }}
        </div>

        <div class="mb-4">
            <strong>Objetivo:</strong> {{ $actividad->objetivo }}
        </div>

        <div class="mb-4">
            <strong>Frecuencia:</strong> {{ $actividad->frecuencia }}
        </div>

        <div class="mb-4">
            <strong>Responsable:</strong> {{ $actividad->responsable }}
        </div>

        <div class="mb-4">
            <strong>Estado:</strong> <span class="capitalize">{{ $actividad->estado }}</span>
        </div>

        <a href="{{ route('plan-actividad.edit', $actividad->id) }}"
            class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
            Editar Actividad
        </a>
        <br><br>
        <div class="mb-6">
            <h2 class="font-semibold">Progreso Actual</h2>

            <div class="w-full bg-gray-200 rounded h-5 overflow-hidden mt-2">
                <div class="h-5 rounded
            @if ($actividad->progreso == 100) bg-green-600
            @elseif($actividad->progreso > 0)
                bg-yellow-500
            @else
                bg-gray-400 @endif"
                    style="width: {{ $actividad->progreso }}%">
                </div>
            </div>

            <p class="text-gray-700 mt-1">{{ $actividad->progreso }}% completado</p>
        </div>

        <div class="bg-white shadow rounded p-6 mt-6">
            <h2 class="text-lg font-bold mb-3">Registrar Avance</h2>

            <form action="{{ route('actividad.ejecucion.store', $actividad->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <label class="block mb-2">Porcentaje de Avance (%)</label>
                <input type="number" name="avance" min="0" max="100" class="border rounded px-3 py-2 w-32"
                    required>

                <label class="block mt-4 mb-2">Comentario</label>
                <textarea name="comentario" class="border rounded w-full px-3 py-2" rows="3"></textarea>

                <label class="block mt-4 mb-2">Fecha de Ejecución</label>
                <input type="date" name="fecha" class="border rounded px-3 py-2">

                <label class="block mt-4 mb-2">Evidencia (opcional)</label>
                <input type="file" name="evidencia" class="border rounded px-3 py-2">

                <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar Avance
                </button>
            </form>
        </div>
        @if ($actividad->ejecuciones->count() > 0)
            <div class="bg-white shadow rounded p-6 mt-6">
                <h2 class="text-lg font-bold mb-4">Historial de Ejecución</h2>

                <ul class="space-y-3">
                    @foreach ($actividad->ejecuciones as $e)
                        <li class="border rounded p-3">
                            <p class="font-semibold">
                                Avance: {{ $e->avance }}%
                                — {{ $e->fecha ? \Carbon\Carbon::parse($e->fecha)->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                            <p class="text-gray-700">{{ $e->comentario }}</p>

                            @if ($e->evidencia)
                                <a href="{{ asset('storage/' . $e->evidencia) }}" target="_blank"
                                    class="text-blue-600 underline">Ver evidencia</a>
                            @endif

                            <p class="text-xs text-gray-500 mt-1">
                                Registrado por: {{ $e->usuario->name }} el {{ $e->created_at->format('d/m/Y H:i') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

</x-app-layout>
