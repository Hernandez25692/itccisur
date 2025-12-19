<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-6 px-4">

        <div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 space-y-6">

            <!-- TÍTULO -->
            <div class="text-center">
                <h1 class="text-xl font-bold text-gray-800">
                    Editar Antecedente Registral
                </h1>
                <p class="text-sm text-gray-500">
                    Centro Asociado del Sur
                </p>
            </div>

            <!-- ERRORES -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded text-sm">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('gor.antecedentes.update', $registro->id) }}"
                enctype="multipart/form-data" class="space-y-5">

                @csrf
                @method('PUT')

                <!-- CIRCUNSCRIPCIÓN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Circunscripción Registral
                    </label>

                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 border rounded-lg">
                            <input type="radio" name="circunscripcion" value="Nacaome - Valle"
                                {{ $registro->circunscripcion === 'Nacaome - Valle' ? 'checked' : '' }} required>
                            <span>Nacaome / Valle</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 border rounded-lg">
                            <input type="radio" name="circunscripcion" value="Choluteca - Choluteca"
                                {{ $registro->circunscripcion === 'Choluteca - Choluteca' ? 'checked' : '' }}>
                            <span>Choluteca / Choluteca</span>
                        </label>
                    </div>
                </div>

                <!-- FECHA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Fecha de Recepción
                    </label>
                    <input type="date" name="fecha_recepcion" value="{{ $registro->fecha_recepcion }}"
                        class="mt-1 w-full rounded-lg border-gray-300" required>
                </div>

                <!-- DATOS SOLICITANTE -->
                <div class="border-t pt-4">
                    <h2 class="font-semibold text-gray-700 mb-3">
                        Datos del Solicitante
                    </h2>

                    <div class="space-y-4">
                        <input type="text" name="solicitante_nombre" value="{{ $registro->solicitante_nombre }}"
                            class="w-full rounded-lg border-gray-300" required>

                        <textarea name="solicitante_direccion" rows="2" class="w-full rounded-lg border-gray-300">{{ $registro->solicitante_direccion }}</textarea>
                    </div>
                </div>

                <!-- DATOS REGISTRALES -->
                <div class="border-t pt-4 space-y-4">
                    <input type="text" name="numero_exequatur" value="{{ $registro->numero_exequatur }}"
                        placeholder="Número de Exequátur" class="w-full rounded-lg border-gray-300">

                    <input type="text" name="asiento_tomo_matricula" value="{{ $registro->asiento_tomo_matricula }}"
                        placeholder="Asiento / Tomo / Matrícula" class="w-full rounded-lg border-gray-300">

                    <input type="text" name="tipo_libro" value="{{ $registro->tipo_libro }}"
                        placeholder="Tipo de libro" class="w-full rounded-lg border-gray-300">
                </div>

                <!-- MOTIVO -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Motivo
                    </label>
                    <textarea name="motivo" rows="4" class="mt-1 w-full rounded-lg border-gray-300">{{ $registro->motivo }}</textarea>
                </div>
                <!-- COMPROBANTE -->
                <div class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Comprobante
                    </label>

                    @if ($registro->comprobante_path)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $registro->comprobante_path) }}" target="_blank"
                                class="text-sm text-blue-700 underline">
                                Ver comprobante actual
                            </a>
                        </div>
                    @endif

                    <input type="file" name="comprobante" accept="image/*" capture="environment"
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm bg-white">

                    <p class="text-xs text-gray-500 mt-1">
                        Si selecciona uno nuevo, reemplazará el anterior.
                    </p>
                </div>

                <!-- BOTONES -->
                <div class="pt-4 space-y-3">
                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg">
                        Actualizar Registro
                    </button>

                    <a href="{{ route('gor.antecedentes.index') }}"
                        class="block text-center text-sm text-gray-600 underline">
                        Volver al listado
                    </a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
