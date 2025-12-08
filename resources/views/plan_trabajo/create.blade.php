<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header del formulario -->
        <div class="mb-8">
            <a href="{{ route('plan-trabajo.index') }}" 
               class="inline-flex items-center gap-2 text-[#0C1C3C] hover:text-[#1A2A4F] font-medium mb-6 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Planes de Trabajo
            </a>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-gradient-to-br from-[#C5A049] to-[#D8B96E] rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C]">Crear Plan de Trabajo TI</h1>
                    <p class="text-gray-600 mt-1">Define un nuevo plan estratégico anual para el departamento de Tecnologías de la Información</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta del formulario -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Encabezado de la tarjeta -->
            <div class="bg-gradient-to-r from-[#C5A049] to-[#D8B96E] px-8 py-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-[#0C1C3C]">Información del Nuevo Plan</h2>
                        <p class="text-[#0C1C3C]/80 text-sm">Completa los campos obligatorios para crear el plan</p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('plan-trabajo.store') }}" method="POST" class="p-8">
                @csrf

                <!-- Mensajes de validación -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-red-800">Corrige los siguientes errores:</h3>
                        </div>
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Grid de campos -->
                <div class="space-y-8">
                    <!-- Año del Plan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Año del Plan
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="anio" 
                                   value="{{ old('anio', date('Y')) }}"
                                   class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('anio') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                   placeholder="Ej: 2024"
                                   min="2024" 
                                   max="2100" 
                                   required>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                año
                            </div>
                        </div>
                        @error('anio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button type="button" 
                                    onclick="setYear({{ date('Y') }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                                Actual ({{ date('Y') }})
                            </button>
                            <button type="button" 
                                    onclick="setYear({{ date('Y') + 1 }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                                Próximo ({{ date('Y') + 1 }})
                            </button>
                            <button type="button" 
                                    onclick="setYear({{ date('Y') - 1 }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                                Anterior ({{ date('Y') - 1 }})
                            </button>
                            <button type="button" 
                                    onclick="setYear({{ date('Y') + 2 }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                                Futuro ({{ date('Y') + 2 }})
                            </button>
                        </div>
                    </div>

                    <!-- Descripción General -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Descripción General
                                <span class="text-xs font-normal text-gray-400">(Opcional)</span>
                            </span>
                        </label>
                        <textarea name="descripcion_general" 
                                  rows="6"
                                  class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('descripcion_general') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors resize-none"
                                  placeholder="Describe los objetivos estratégicos del plan, áreas de enfoque principales y metas generales para el año...">{{ old('descripcion_general') }}</textarea>
                        @error('descripcion_general')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Plantillas sugeridas -->
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Plantillas sugeridas:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <button type="button" 
                                        onclick="setTemplate('infraestructura')"
                                        class="text-left p-3 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 transition-colors">
                                    <p class="text-sm font-medium text-blue-800">Infraestructura TI</p>
                                    <p class="text-xs text-blue-600 mt-1">Modernización de servidores, redes y equipos</p>
                                </button>
                                <button type="button" 
                                        onclick="setTemplate('seguridad')"
                                        class="text-left p-3 bg-green-50 border border-green-100 rounded-xl hover:bg-green-100 transition-colors">
                                    <p class="text-sm font-medium text-green-800">Seguridad Informática</p>
                                    <p class="text-xs text-green-600 mt-1">Ciberseguridad, auditorías y políticas</p>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Inicial -->
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Configuración Inicial
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="font-medium text-gray-900">Estado Inicial</label>
                                        <p class="text-sm text-gray-500">El plan comenzará como borrador</p>
                                    </div>
                                </div>
                                <div class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium">
                                    Borrador
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-purple-50 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="font-medium text-gray-900">Creado por</label>
                                        <p class="text-sm text-gray-500">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-[#C5A049] to-[#D8B96E] flex items-center justify-center text-[#0C1C3C] text-xs font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-200">
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Los datos se guardan de forma segura
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="resetForm()"
                                class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            Limpiar
                        </button>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                            <svg class="w-5 h-5 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Crear Plan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Información de ayuda -->
        <div class="mt-8 bg-gradient-to-r from-[#0C1C3C]/5 to-[#1A2A4F]/5 rounded-2xl p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-[#0C1C3C] mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recomendaciones para crear planes
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Año específico</h4>
                        <p class="text-sm text-gray-600">Cada plan corresponde a un año fiscal específico, normalmente el año en curso o próximo.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Descripción clara</h4>
                        <p class="text-sm text-gray-600">Incluye objetivos generales, áreas de enfoque y metas principales para el año.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para mejoras de UX -->
    <script>
        // Función para establecer año rápidamente
        function setYear(year) {
            const yearInput = document.querySelector('input[name="anio"]');
            if (yearInput) {
                yearInput.value = year;
                yearInput.focus();
            }
        }
        
        // Función para establecer plantillas de descripción
        function setTemplate(templateType) {
            const textarea = document.querySelector('textarea[name="descripcion_general"]');
            if (!textarea) return;
            
            const templates = {
                'infraestructura': 'Plan anual de infraestructura tecnológica que incluye:\n\n1. Modernización de servidores y almacenamiento\n2. Actualización de equipos de red y seguridad perimetral\n3. Implementación de soluciones de respaldo y recuperación ante desastres\n4. Optimización de centros de datos y virtualización\n\nObjetivo: Garantizar la disponibilidad, escalabilidad y seguridad de la infraestructura TI.',
                'seguridad': 'Plan de seguridad informática que contempla:\n\n1. Auditorías periódicas de seguridad\n2. Implementación de políticas y controles de acceso\n3. Capacitación en conciencia de seguridad para usuarios\n4. Monitoreo continuo de amenazas y vulnerabilidades\n\nObjetivo: Fortalecer la postura de seguridad y cumplir con normativas aplicables.'
            };
            
            if (templates[templateType]) {
                textarea.value = templates[templateType];
                textarea.focus();
            }
        }
        
        // Función para limpiar el formulario
        function resetForm() {
            if (confirm('¿Estás seguro de que deseas limpiar todos los campos del formulario?')) {
                document.querySelector('form').reset();
                const yearInput = document.querySelector('input[name="anio"]');
                if (yearInput) {
                    yearInput.value = new Date().getFullYear();
                }
            }
        }
        
        // Validación básica del año
        document.querySelector('input[name="anio"]')?.addEventListener('input', function(e) {
            let value = parseInt(this.value);
            if (value < 2024) this.value = 2024;
            if (value > 2100) this.value = 2100;
        });
    </script>
</x-app-layout>