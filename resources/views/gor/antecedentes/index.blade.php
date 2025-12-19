<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4">

        <div class="max-w-4xl mx-auto space-y-4">

            <!-- ENCABEZADO -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">
                        Revisión de Antecedentes Registrales
                    </h1>
                    <p class="text-sm text-gray-500">Centro Asociado del Sur</p>
                </div>

                <a href="{{ route('gor.antecedentes.create') }}"
                    class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    + Nuevo
                </a>
            </div>

            <!-- 🔎 FILTROS -->
            <form method="GET" class="bg-white rounded-xl shadow p-4 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <!-- Circunscripción -->
                    <select name="circunscripcion" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Todas las circunscripciones</option>
                        <option value="Nacaome - Valle"
                            {{ request('circunscripcion') == 'Nacaome - Valle' ? 'selected' : '' }}>
                            Nacaome / Valle
                        </option>
                        <option value="Choluteca - Choluteca"
                            {{ request('circunscripcion') == 'Choluteca - Choluteca' ? 'selected' : '' }}>
                            Choluteca / Choluteca
                        </option>
                    </select>

                    <!-- Fecha desde -->
                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                        class="rounded-lg border-gray-300 text-sm" placeholder="Desde">

                    <!-- Fecha hasta -->
                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                        class="rounded-lg border-gray-300 text-sm" placeholder="Hasta">

                    <!-- Texto libre -->
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="rounded-lg border-gray-300 text-sm" placeholder="Nombre o Exequátur">
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('gor.antecedentes.index') }}" class="px-4 py-2 text-sm text-gray-600 underline">
                        Limpiar
                    </a>

                    <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">
                        Filtrar
                    </button>
                </div>
            </form>

            <!-- LISTADO -->
            @foreach ($registros as $registro)
                <div class="bg-white rounded-xl shadow p-4 space-y-3">

                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $registro->solicitante_nombre }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $registro->circunscripcion }}
                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            {{ $registro->created_at->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-700 space-y-1">
                        <p><strong>Recepción:</strong>
                            {{ \Carbon\Carbon::parse($registro->fecha_recepcion)->format('d/m/Y') }}
                        </p>

                        @if ($registro->numero_exequatur)
                            <p><strong>Exequátur:</strong> {{ $registro->numero_exequatur }}</p>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500">
                        Registrado por:
                        <span class="font-medium">
                            {{ $registro->creador->name ?? 'N/D' }}
                        </span>
                    </div>

                    <div class="flex justify-end gap-4 text-sm">
                        <a href="{{ route('gor.antecedentes.show', $registro->id) }}" class="text-gray-700 underline">
                            Ver
                        </a>
                        <a href="{{ route('gor.antecedentes.edit', $registro->id) }}"
                            class="text-blue-700 font-medium">
                            Editar
                        </a>
                    </div>
                </div>
            @endforeach

            {{ $registros->links() }}

        </div>
    </div>
</x-app-layout>
