<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-6 px-4">

        <div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 space-y-6">

            <!-- TÍTULO -->
            <div class="text-center">
                <h1 class="text-xl font-bold text-gray-800">
                    Revisión de Antecedentes Registrales
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

            <form method="POST" action="{{ route('gor.antecedentes.store') }}" class="space-y-5">
                @csrf

                <!-- CIRCUNSCRIPCIÓN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Circunscripción Registral
                    </label>

                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 border rounded-lg">
                            <input type="radio" name="circunscripcion" value="Nacaome - Valle" required
                                onchange="actualizarTipoLibro()">
                            <span>Nacaome / Valle</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 border rounded-lg">
                            <input type="radio" name="circunscripcion" value="Choluteca - Choluteca"
                                onchange="actualizarTipoLibro()">
                            <span>Choluteca / Choluteca</span>
                        </label>
                    </div>
                </div>

                <!-- FECHA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Fecha de Recepción
                    </label>
                    <input type="date" name="fecha_recepcion" class="mt-1 w-full rounded-lg border-gray-300"
                        required>
                </div>

                <!-- DATOS SOLICITANTE -->
                <div class="border-t pt-4">
                    <h2 class="font-semibold text-gray-700 mb-3">
                        Datos del Solicitante
                    </h2>

                    <div class="space-y-4">
                        <input type="text" name="solicitante_nombre" placeholder="Nombre completo"
                            class="w-full rounded-lg border-gray-300" required>

                        <textarea name="solicitante_direccion" rows="2" placeholder="Dirección" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                </div>

                <!-- DATOS REGISTRALES -->
                <div class="border-t pt-4 space-y-4">
                    <input type="text" name="numero_exequatur" placeholder="Número de Exequátur"
                        class="w-full rounded-lg border-gray-300">

                    <input type="text" name="asiento_tomo_matricula" placeholder="Asiento / Tomo / Matrícula"
                        class="w-full rounded-lg border-gray-300">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de libro</label>
                        <select id="tipoLibro" name="tipo_libro" class="w-full rounded-lg border-gray-300">
                            <option value="">[Seleccionar]</option>
                        </select>
                    </div>
                </div>

                <!-- MOTIVO -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Motivo
                    </label>
                    <textarea name="motivo" rows="4" class="mt-1 w-full rounded-lg border-gray-300" placeholder="Describa el motivo"></textarea>
                </div>

                <!-- BOTÓN -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg">
                        Guardar Registro
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        const librosCholulteca = {
            "01 - REGISTRO DE LA PROPIEDAD": "01 - REGISTRO DE LA PROPIEDAD",
            "02 - REGISTRO DE HIPOTECAS": "02 - REGISTRO DE HIPOTECAS",
            "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS": "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS",
            "04 - LIBROS INA": "04 - LIBROS INA",
            "05 - LIBRO TOMO CONSERVADOR": "05 - LIBRO TOMO CONSERVADOR",
            "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE": "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE",
            "07 - LIBRO DE PRESENTACIONES (DIARIO)": "07 - LIBRO DE PRESENTACIONES (DIARIO)",
            "10 - CERTIFICACIONES MUNICIPALES": "10 - CERTIFICACIONES MUNICIPALES",
            "11 - ANOTACIONES PREVENTIVAS": "11 - ANOTACIONES PREVENTIVAS",
            "12 - REINSCRIPCIONES": "12 - REINSCRIPCIONES",
            "13 - LIBRO DE SENTENCIAS": "13 - LIBRO DE SENTENCIAS",
            "14 - PROPIEDAD HORIZONTAL": "14 - PROPIEDAD HORIZONTAL",
            "15 - AUXILIAR DE PRENDAS": "15 - AUXILIAR DE PRENDAS",
            "16 - PRENDAS MERCANTILES": "16 - PRENDAS MERCANTILES",
            "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS": "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS",
            "18 - REGISTRO DE AERONAUTICA": "18 - REGISTRO DE AERONAUTICA",
            "21 - REGISTRO COMERCIANTE SOCIAL": "21 - REGISTRO COMERCIANTE SOCIAL",
            "22 - REGISTRO COMERCIANTE INDIVIDUAL": "22 - REGISTRO COMERCIANTE INDIVIDUAL",
            "24 - ESTABLECIMIENTO MERCANTIL": "24 - ESTABLECIMIENTO MERCANTIL",
            "30 - REGISTRO DE PODERES": "30 - REGISTRO DE PODERES",
            "32 - COMERCIANTE INDIVIDUAL (ANTIGUO)": "32 - COMERCIANTE INDIVIDUAL (ANTIGUO)",
            "33 - REGISTRO DE COMERCIO (ANTIGUO)": "33 - REGISTRO DE COMERCIO (ANTIGUO)",
            "34 - REGISTRO MERCANTIL (ANTIGUO)": "34 - REGISTRO MERCANTIL (ANTIGUO)",
            "19 - CIRCUNSCRIPCION": "19 - CIRCUNSCRIPCION"
        };

        const librosNacaome = {
            "01 - REGISTRO DE LA PROPIEDAD": "01 - REGISTRO DE LA PROPIEDAD",
            "02 - REGISTRO DE HIPOTECAS": "02 - REGISTRO DE HIPOTECAS",
            "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS": "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS",
            "04 - LIBROS INA": "04 - LIBROS INA",
            "05 - LIBRO TOMO CONSERVADOR": "05 - LIBRO TOMO CONSERVADOR",
            "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE": "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE",
            "07 - LIBRO DE PRESENTACIONES (DIARIO)": "07 - LIBRO DE PRESENTACIONES (DIARIO)",
            "09 - LIBRO DE INSCRIPCIONES": "09 - LIBRO DE INSCRIPCIONES",
            "10 - CERTIFICACIONES MUNICIPALES": "10 - CERTIFICACIONES MUNICIPALES",
            "11 - ANOTACIONES PREVENTIVAS": "11 - ANOTACIONES PREVENTIVAS",
            "12 - REINSCRIPCIONES": "12 - REINSCRIPCIONES",
            "13 - LIBRO DE SENTENCIAS": "13 - LIBRO DE SENTENCIAS",
            "14 - PROPIEDAD HORIZONTAL": "14 - PROPIEDAD HORIZONTAL",
            "15 - AUXILIAR DE PRENDAS": "15 - AUXILIAR DE PRENDAS",
            "16 - PRENDAS MERCANTILES": "16 - PRENDAS MERCANTILES",
            "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS": "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS",
            "18 - REGISTRO DE AERONAUTICA": "18 - REGISTRO DE AERONAUTICA",
            "21 - REGISTRO COMERCIANTE SOCIAL": "21 - REGISTRO COMERCIANTE SOCIAL",
            "22 - REGISTRO COMERCIANTE INDIVIDUAL": "22 - REGISTRO COMERCIANTE INDIVIDUAL",
            "24 - ESTABLECIMIENTO MERCANTIL": "24 - ESTABLECIMIENTO MERCANTIL",
            "30 - REGISTRO DE PODERES": "30 - REGISTRO DE PODERES",
            "34 - REGISTRO MERCANTIL (ANTIGUO)": "34 - REGISTRO MERCANTIL (ANTIGUO)",
            "35 - LIBRO DE ACTAS DE REGULARIZACION PREDIAL": "35 - LIBRO DE ACTAS DE REGULARIZACION PREDIAL",
            "36 - PERSONERIAS JURIDICAS": "36 - PERSONERIAS JURIDICAS",
            "19 - CIRCUNSCRIPCION": "19 - CIRCUNSCRIPCION"
        };

        function actualizarTipoLibro() {
            const select = document.getElementById('tipoLibro');
            const circunscripcion = document.querySelector('input[name="circunscripcion"]:checked')?.value;
            
            select.innerHTML = '<option value="">[Seleccionar]</option>';
            
            const libros = circunscripcion === 'Nacaome - Valle' ? librosNacaome : librosCholulteca;
            
            Object.entries(libros).forEach(([valor, texto]) => {
                const option = document.createElement('option');
                option.value = valor;
                option.textContent = texto;
                select.appendChild(option);
            });
        }
    </script>
</x-app-layout>
