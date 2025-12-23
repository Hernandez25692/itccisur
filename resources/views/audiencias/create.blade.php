<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6 md:py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <!-- Encabezado de navegación -->
            <div class="mb-6 md:mb-8">
                <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                    <a href="{{ route('audiencias.index') }}" class="hover:text-gray-900 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <span>/</span>
                    <a href="{{ route('audiencias.index') }}" class="hover:text-gray-900 transition duration-200">
                        Audiencias
                    </a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Nueva Audiencia</span>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                            Registrar Nueva Audiencia
                        </h1>
                        <p class="text-gray-600 mt-2">
                            Complete el formulario para registrar una nueva audiencia en el sistema
                        </p>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span>Formulario de registro</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta del formulario -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Encabezado de la tarjeta -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Información de la Audiencia</h2>
                            <p class="text-sm text-gray-600">Los campos marcados con <span class="text-red-500">*</span>
                                son obligatorios</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('audiencias.store') }}" class="p-6 md:p-8">
                    @csrf

                    <div class="space-y-8">
                        <!-- Sección 1: Información básica -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="h-px flex-1 bg-gradient-to-r from-blue-500 to-transparent"></div>
                                <h3
                                    class="text-sm font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Información Básica
                                </h3>
                                <div class="h-px flex-1 bg-gradient-to-l from-blue-500 to-transparent"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre solicitante -->
                                <div class="space-y-2">
                                    <label for="nombre_solicitante" class="block text-sm font-medium text-gray-700">
                                        Nombre de quien solicita <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="nombre_solicitante" name="nombre_solicitante" required
                                            class="pl-10 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200 py-3"
                                            placeholder="Ingrese el nombre completo"
                                            value="{{ old('nombre_solicitante') }}">
                                    </div>
                                    @error('nombre_solicitante')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha de recepción -->
                                <div class="space-y-2">
                                    <label for="fecha_recepcion" class="block text-sm font-medium text-gray-700">
                                        Fecha de recepción <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="date" id="fecha_recepcion" name="fecha_recepcion" required
                                            class="pl-10 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200 py-3"
                                            value="{{ old('fecha_recepcion') ?? date('Y-m-d') }}">
                                    </div>
                                    @error('fecha_recepcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Documentación -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="h-px flex-1 bg-gradient-to-r from-blue-500 to-transparent"></div>
                                <h3
                                    class="text-sm font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Documentación
                                </h3>
                                <div class="h-px flex-1 bg-gradient-to-l from-blue-500 to-transparent"></div>
                            </div>

                            <div class="space-y-2">
                                <label for="numero_documento" class="block text-sm font-medium text-gray-700">
                                    N° Presentación/Matrícula/Asiento/Tomo <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="numero_documento" name="numero_documento" required
                                        class="pl-10 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200 py-3"
                                        placeholder="Ej: 2024-001234" value="{{ old('numero_documento') }}">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Ingrese el número de identificación del documento
                                </p>
                                @error('numero_documento')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección 3: Detalles de la audiencia -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="h-px flex-1 bg-gradient-to-r from-blue-500 to-transparent"></div>
                                <h3
                                    class="text-sm font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Detalles de la Audiencia
                                </h3>
                                <div class="h-px flex-1 bg-gradient-to-l from-blue-500 to-transparent"></div>
                            </div>

                            <!-- Motivo -->
                            <div class="space-y-2">
                                <label for="motivo" class="block text-sm font-medium text-gray-700">
                                    Motivo de la audiencia <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                    <textarea id="motivo" name="motivo" rows="4" required
                                        class="pl-10 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200 py-3"
                                        placeholder="Describa el motivo de la audiencia...">{{ old('motivo') }}</textarea>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Describa detalladamente el motivo de la solicitud de audiencia
                                </p>
                                @error('motivo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fecha y hora de atención -->
                            <div class="space-y-2">
                                <label for="fecha_hora_atencion" class="block text-sm font-medium text-gray-700">
                                    Fecha y hora de atención (opcional)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input type="datetime-local" id="fecha_hora_atencion" name="fecha_hora_atencion"
                                        class="pl-10 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200 py-3"
                                        value="{{ old('fecha_hora_atencion') }}">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Si la audiencia ya fue atendida, especifique la fecha y hora
                                </p>
                                @error('fecha_hora_atencion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dictamen -->
                            <div class="space-y-2">
                                <label for="dictamen" class="block text-sm font-medium text-gray-700">
                                    Dictamen (opcional)
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <textarea id="dictamen" name="dictamen" rows="4"
                                        class="pl-10 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200 py-3"
                                        placeholder="Ingrese el dictamen si aplica...">{{ old('dictamen') }}</textarea>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Dictamen o resolución de la audiencia
                                </p>
                                @error('dictamen')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="mt-10 pt-8 border-t border-gray-200">
                        <div class="flex flex-col-reverse sm:flex-row justify-between gap-4">
                            <a href="{{ route('audiencias.index') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-200 shadow-sm hover:shadow">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Guardar Audiencia
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Nota informativa -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900">Información importante</h3>
                        <div class="mt-1 text-sm text-blue-800">
                            <p>• Todos los campos marcados con <span class="text-red-500">*</span> son obligatorios.
                            </p>
                            <p>• La fecha de atención es opcional y puede ser completada posteriormente.</p>
                            <p>• Verifique la información antes de guardar los cambios.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para mejoras de UI -->
    <script>
        // Establecer fecha mínima para campos de fecha
        document.addEventListener('DOMContentLoaded', function() {
            const fechaRecepcion = document.getElementById('fecha_recepcion');
            const fechaAtencion = document.getElementById('fecha_hora_atencion');

            // Establecer fecha actual como valor por defecto si no hay valor previo
            if (!fechaRecepcion.value) {
                const today = new Date().toISOString().split('T')[0];
                fechaRecepcion.value = today;
            }

            // Establecer min/max para fechas
            const today = new Date().toISOString().split('T')[0];
            fechaRecepcion.max = today;

            // Si hay fecha de recepción, establecer como mínima para fecha de atención
            fechaRecepcion.addEventListener('change', function() {
                if (fechaAtencion) {
                    fechaAtencion.min = this.value + 'T00:00';
                }
            });

            // Inicializar si ya hay valor
            if (fechaRecepcion.value && fechaAtencion) {
                fechaAtencion.min = fechaRecepcion.value + 'T00:00';
            }

            // Mejorar la experiencia de los campos de texto
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-200', 'ring-opacity-50');
                });

                textarea.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-200',
                        'ring-opacity-50');
                });
            });

            // Contador de caracteres para textareas
            const motivoTextarea = document.getElementById('motivo');
            if (motivoTextarea) {
                const motivoCounter = document.createElement('div');
                motivoCounter.className = 'text-xs text-gray-500 text-right mt-1';
                motivoCounter.textContent = `${motivoTextarea.value.length}/500`;

                motivoTextarea.parentElement.appendChild(motivoCounter);

                motivoTextarea.addEventListener('input', function() {
                    motivoCounter.textContent = `${this.value.length}/500`;

                    if (this.value.length > 500) {
                        motivoCounter.classList.add('text-red-500');
                    } else {
                        motivoCounter.classList.remove('text-red-500');
                    }
                });
            }
        });

        // Validación antes de enviar
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500', 'bg-red-50');

                    // Remover la clase después de un tiempo
                    setTimeout(() => {
                        field.classList.remove('border-red-500', 'bg-red-50');
                    }, 3000);
                }
            });

            if (!isValid) {
                e.preventDefault();

                // Mostrar mensaje de error
                const errorAlert = document.createElement('div');
                errorAlert.className =
                    'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow-lg z-50 animate-slide-in';
                errorAlert.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Por favor, complete todos los campos obligatorios.</span>
                    </div>
                `;

                document.body.appendChild(errorAlert);

                setTimeout(() => {
                    errorAlert.remove();
                }, 5000);

                // Scroll al primer campo con error
                const firstError = this.querySelector('[required]:invalid');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstError.focus();
                }
            }
        });
    </script>

    <!-- Estilos personalizados -->
    <style>
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Mejoras para inputs y textareas */
        input,
        textarea,
        select {
            transition: all 0.2s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Scroll personalizado para textareas */
        textarea {
            resize: vertical;
            min-height: 100px;
            max-height: 300px;
        }

        /* Estilos para errores */
        .border-red-500 {
            border-color: #ef4444;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        /* Mejoras responsivas */
        @media (max-width: 640px) {
            .text-3xl {
                font-size: 1.75rem;
            }

            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .py-3 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .gap-6 {
                gap: 1rem;
            }
        }
    </style>
</x-app-layout>
