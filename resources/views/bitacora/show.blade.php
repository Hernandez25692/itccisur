<x-app-layout>

    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-lg p-8">

        {{-- ENCABEZADO --}}
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $actividad->titulo }}
                </h1>
                <p class="text-sm text-gray-500">
                    Registrado por <strong>{{ $actividad->user->name }}</strong>
                    el {{ $actividad->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <a href="{{ route('bitacora.edit', $actividad->id) }}"
                class="bg-yellow-500 text-white px-4 py-2 rounded shadow hover:bg-yellow-600 transition">
                Editar
            </a>
        </div>


        {{-- INFORMACIÓN PRINCIPAL --}}
        <div class="grid grid-cols-2 gap-6 mb-6">

            <div>
                <p class="font-semibold text-gray-700">Fecha</p>
                <p class="text-gray-900">{{ $actividad->fecha->format('d/m/Y') }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Prioridad</p>
                <span
                    class="px-3 py-1 rounded text-white
                    @if ($actividad->prioridad == 'critica') bg-red-700
                    @elseif($actividad->prioridad == 'alta') bg-red-500
                    @elseif($actividad->prioridad == 'media') bg-yellow-500
                    @else bg-green-600 @endif">
                    {{ ucfirst($actividad->prioridad) }}
                </span>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Estado</p>
                <span
                    class="px-3 py-1 rounded text-white
                    @if ($actividad->estado == 'resuelto') bg-green-600
                    @elseif($actividad->estado == 'en_proceso') bg-blue-600
                    @else bg-gray-600 @endif">
                    {{ ucfirst($actividad->estado) }}
                </span>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Ubicación</p>
                <p class="text-gray-900">{{ $actividad->ubicacion }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Tipo de Falla</p>
                <p class="text-gray-900">{{ $actividad->tipo_falla }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Equipo Afectado</p>
                <p class="text-gray-900">{{ $actividad->equipo_afectado }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Hora Inicio</p>
                <p class="text-gray-900">
                    {{ $actividad->hora_inicio ? \Carbon\Carbon::parse($actividad->hora_inicio)->format('H:i') : '—' }}
                </p>
            </div>

            <div>
                <p class="font-semibold text-gray-700">Hora Fin</p>
                <p class="text-gray-900">
                    {{ $actividad->hora_fin ? \Carbon\Carbon::parse($actividad->hora_fin)->format('H:i') : '—' }}
                </p>
            </div>

        </div>


        {{-- DESCRIPCIÓN --}}
        @if ($actividad->descripcion)
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">Descripción</h2>
                <p class="text-gray-700 leading-relaxed">{{ $actividad->descripcion }}</p>
            </div>
        @endif

        {{-- SOLUCIÓN --}}
        @if ($actividad->solucion_aplicada)
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2 text-green-700">Solución Aplicada</h2>
                <p class="text-gray-700 leading-relaxed">{{ $actividad->solucion_aplicada }}</p>
            </div>
        @endif


        {{-- EVIDENCIA --}}
        @if ($actividad->evidencia)
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">Evidencia</h2>

                <img src="{{ asset('storage/' . $actividad->evidencia) }}" class="rounded shadow-lg max-h-80 border"
                    alt="Evidencia">
            </div>
        @endif


        {{-- TIEMPO EMPLEADO --}}
        @if ($actividad->tiempo_empleado_minutos)
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">Tiempo Empleado</h2>
                <p class="text-gray-700">
                    {{ $actividad->tiempo_empleado_minutos }} minutos
                </p>
            </div>
        @endif


        {{-- BOTÓN VOLVER --}}
        <div class="mt-6">
            <a href="{{ route('bitacora.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                ← Volver a Bitácora
            </a>
        </div>

    </div>

</x-app-layout>
