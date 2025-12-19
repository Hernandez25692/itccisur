<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-6 px-4">

        <div class="max-w-3xl mx-auto space-y-4">

            <!-- ENCABEZADO -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">
                        Antecedentes Registrales
                    </h1>
                    <p class="text-sm text-gray-500">
                        Centro Asociado del Sur
                    </p>
                </div>

                <a href="{{ route('gor.antecedentes.create') }}"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm">
                    + Nuevo
                </a>
            </div>

            <!-- MENSAJE ÉXITO -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- LISTADO -->
            @forelse ($registros as $registro)
                <div class="bg-white rounded-xl shadow p-4 space-y-2">

                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $registro->solicitante_nombre }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $registro->circunscripcion }}
                            </p>
                        </div>

                        <a href="{{ route('gor.antecedentes.edit', $registro->id) }}"
                            class="text-blue-700 text-sm font-medium">
                            Editar
                        </a>
                    </div>

                    <div class="text-sm text-gray-600 space-y-1">
                        <p>
                            <strong>Fecha:</strong>
                            {{ \Carbon\Carbon::parse($registro->fecha_recepcion)->format('d/m/Y') }}
                        </p>

                        @if ($registro->numero_exequatur)
                            <p>
                                <strong>Exequátur:</strong>
                                {{ $registro->numero_exequatur }}
                            </p>
                        @endif

                        @if ($registro->asiento_tomo_matricula)
                            <p>
                                <strong>Asiento / Tomo / Matrícula:</strong>
                                {{ $registro->asiento_tomo_matricula }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                    No hay registros aún.
                </div>
            @endforelse

            <!-- PAGINACIÓN -->
            <div class="pt-4">
                {{ $registros->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
