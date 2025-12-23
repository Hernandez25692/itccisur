<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-5xl mx-auto space-y-4">

            <!-- ALERTA -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ENCABEZADO -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">
                        Control de Audiencias
                    </h1>
                    <p class="text-sm text-gray-500">
                        Registro y seguimiento de audiencias
                    </p>
                </div>

                <a href="{{ route('audiencias.create') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    + Nueva
                </a>
            </div>
            <!-- RESUMEN / KPIs -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <!-- Total -->
                <div class="bg-white rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Total de audiencias</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $total }}
                    </p>
                </div>

                <!-- Atendidas -->
                <div class="bg-green-50 rounded-xl shadow p-4 border border-green-200">
                    <p class="text-sm text-green-700">Atendidas</p>
                    <p class="text-2xl font-bold text-green-800">
                        {{ $atendidos }}
                    </p>
                </div>

                <!-- Pendientes -->
                <div class="bg-yellow-50 rounded-xl shadow p-4 border border-yellow-200">
                    <p class="text-sm text-yellow-700">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-800">
                        {{ $pendientes }}
                    </p>
                </div>

            </div>

            <!-- FILTROS -->
            <form method="GET" class="bg-white rounded-xl shadow p-4 space-y-3">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">

                    <input type="text" name="nombre" value="{{ request('nombre') }}"
                        class="rounded-lg border-gray-300 text-sm" placeholder="Solicitante">

                    <input type="text" name="documento" value="{{ request('documento') }}"
                        class="rounded-lg border-gray-300 text-sm" placeholder="Documento">

                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                        class="rounded-lg border-gray-300 text-sm">

                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                        class="rounded-lg border-gray-300 text-sm">

                    <select name="estado" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                            Pendiente
                        </option>
                        <option value="atendido" {{ request('estado') == 'atendido' ? 'selected' : '' }}>
                            Atendido
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('audiencias.index') }}" class="text-sm text-gray-600 underline">
                        Limpiar
                    </a>

                    <button class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">
                        Filtrar
                    </button>
                </div>
            </form>

            <!-- LISTADO -->
            @forelse ($audiencias as $audiencia)
                <div class="bg-white rounded-xl shadow p-4 space-y-2">

                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $audiencia->nombre_solicitante }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Doc: {{ $audiencia->numero_documento }}
                            </p>
                        </div>

                        <div class="text-right">
                            <span class="text-xs text-gray-400">
                                {{ $audiencia->fecha_recepcion->format('d/m/Y') }}
                            </span>

                            <div class="mt-1">
                                @if ($audiencia->fecha_hora_atencion)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                        Atendido
                                    </span>
                                @else
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">
                                        Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700">
                        {{ Str::limit($audiencia->motivo, 120) }}
                    </p>

                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span>
                            Registrado por: {{ $audiencia->creador->name ?? 'N/D' }}
                        </span>

                        <div class="flex gap-4 text-sm">
                            <a href="{{ route('audiencias.show', $audiencia->id) }}" class="underline text-gray-700">
                                Ver
                            </a>
                            <a href="{{ route('audiencias.edit', $audiencia->id) }}" class="text-blue-700 font-medium">
                                Editar
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                    No hay registros con los filtros aplicados.
                </div>
            @endforelse

            <!-- PAGINACIÓN -->
            <div>
                {{ $audiencias->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
