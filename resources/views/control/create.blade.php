<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <!-- Header del formulario -->
        <div class="mb-8">
            <a href="{{ route('control.index') }}" 
               class="inline-flex items-center gap-2 text-[#0C1C3C] hover:text-[#1A2A4F] font-medium mb-4 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Control TI
            </a>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-gradient-to-br from-[#C5A049] to-[#D8B96E] rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C]">Nuevo Registro de Control TI</h1>
                    <p class="text-gray-600 mt-1">Agrega un nuevo registro de licencia, dominio o servicio tecnológico</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta del formulario -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Encabezado de la tarjeta -->
            <div class="bg-gradient-to-r from-[#C5A049] to-[#D8B96E] px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#0C1C3C]">Formulario de Registro</h2>
                            <p class="text-[#0C1C3C]/80 text-sm">Complete todos los campos obligatorios (*)</p>
                        </div>
                    </div>
                    
                    <!-- Indicador de progreso -->
                    <div class="text-right">
                        <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <svg class="w-4 h-4 text-[#0C1C3C] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-[#0C1C3C]">Nuevo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('control.store') }}" class="p-8" id="nuevoRegistroForm">
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

                <!-- Grid de 2 columnas para campos principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Actividad -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                Actividad
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="actividad" 
                               value="{{ old('actividad') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('actividad') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors placeholder-gray-400"
                               placeholder="Ej: Renovación de licencia Office 365"
                               required
                               autofocus>
                        @error('actividad')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Nombre descriptivo de la actividad o servicio</p>
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                Tipo
                                <span class="text-xs font-normal text-gray-400">(Opcional)</span>
                            </span>
                        </label>
                        <select name="tipo"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('tipo') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                            <option value="">Seleccionar tipo</option>
                            <option value="licencia" {{ old('tipo') == 'licencia' ? 'selected' : '' }}>Licencia</option>
                            <option value="dominio" {{ old('tipo') == 'dominio' ? 'selected' : '' }}>Dominio</option>
                            <option value="servicio" {{ old('tipo') == 'servicio' ? 'selected' : '' }}>Servicio</option>
                            <option value="certificado" {{ old('tipo') == 'certificado' ? 'selected' : '' }}>Certificado SSL</option>
                            <option value="hardware" {{ old('tipo') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                            <option value="software" {{ old('tipo') == 'software' ? 'selected' : '' }}>Software</option>
                            <option value="soporte" {{ old('tipo') == 'soporte' ? 'selected' : '' }}>Soporte Técnico</option>
                            <option value="otros" {{ old('tipo') == 'otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                        @error('tipo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Categoría del registro</p>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Descripción
                            <span class="text-xs font-normal text-gray-400">(Opcional)</span>
                        </span>
                    </label>
                    <textarea name="descripcion" 
                              rows="3"
                              class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('descripcion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors placeholder-gray-400 resize-none"
                              placeholder="Describe detalles importantes como proveedor, número de contrato, responsables, etc.">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid para fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Fecha de Ejecución -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha de Ejecución
                                <span class="text-xs font-normal text-gray-400">(Opcional)</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   name="fecha_ejecucion"
                                   value="{{ old('fecha_ejecucion') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('fecha_ejecucion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                        </div>
                        @error('fecha_ejecucion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Fecha de pago, renovación o implementación</p>
                    </div>

                    <!-- Fecha de Vencimiento -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fecha de Vencimiento
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   name="fecha_vencimiento"
                                   value="{{ old('fecha_vencimiento') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('fecha_vencimiento') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors fecha-vencimiento"
                                   required>
                        </div>
                        @error('fecha_vencimiento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500" id="dias-restantes">
                            <!-- Calculado dinámicamente por JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Configuración de Recordatorio -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        Configuración de Recordatorio
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Días de recordatorio -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                <span class="flex items-center gap-2">
                                    Días de recordatorio
                                    <span class="text-red-500">*</span>
                                </span>
                                <span class="text-sm font-normal text-gray-500">(Entre 1 y 90 días)</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="dias_recordatorio"
                                       value="{{ old('dias_recordatorio', 15) }}"
                                       class="w-full px-4 py-3 bg-white border {{ $errors->has('dias_recordatorio') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors dias-recordatorio"
                                       min="1" 
                                       max="90" 
                                       required>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    días antes
                                </div>
                            </div>
                            @error('dias_recordatorio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                    <span>Recordatorio rápido</span>
                                    <span>Plazo extendido</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="slider-progress" class="h-full bg-gradient-to-r from-[#C5A049] to-[#D8B96E] w-1/3"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <button type="button" class="text-xs text-[#C5A049] hover:text-[#D8B96E]" onclick="setRecordatorio(7)">7 días</button>
                                    <button type="button" class="text-xs text-[#C5A049] hover:text-[#D8B96E]" onclick="setRecordatorio(15)">15 días</button>
                                    <button type="button" class="text-xs text-[#C5A049] hover:text-[#D8B96E]" onclick="setRecordatorio(30)">30 días</button>
                                    <button type="button" class="text-xs text-[#C5A049] hover:text-[#D8B96E]" onclick="setRecordatorio(60)">60 días</button>
                                </div>
                            </div>
                        </div>

                        <!-- Opciones adicionales -->
                        <div class="space-y-6">
                            <!-- Notificación automática -->
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="font-medium text-gray-900">Notificación automática</label>
                                        <p class="text-sm text-gray-500">Activar alertas por defecto</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="notificar" 
                                           value="1" 
                                           {{ old('notificar', true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C5A049]"></div>
                                </label>
                            </div>

                            <!-- Estado inicial -->
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200 opacity-50 cursor-not-allowed">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="font-medium text-gray-400">Marcar como atendido</label>
                                        <p class="text-sm text-gray-400">Disponible solo en edición</p>
                                    </div>
                                </div>
                                <div class="w-11 h-6 bg-gray-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
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
                            Guardar Registro
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
                Recomendaciones para nuevos registros
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Nombres descriptivos</h4>
                        <p class="text-sm text-gray-600">Usa nombres claros que identifiquen fácilmente el servicio o licencia.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Recordatorio oportuno</h4>
                        <p class="text-sm text-gray-600">Configura recordatorios con suficiente anticipación para tomar acciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para mejoras de UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaVencimiento = document.querySelector('.fecha-vencimiento');
            const diasRecordatorio = document.querySelector('.dias-recordatorio');
            const diasRestantes = document.getElementById('dias-restantes');
            const sliderProgress = document.getElementById('slider-progress');
            
            // Configurar fecha mínima como hoy
            const hoy = new Date().toISOString().split('T')[0];
            fechaVencimiento.min = hoy;
            
            // Calcular días restantes al cambiar fecha de vencimiento
            if (fechaVencimiento) {
                fechaVencimiento.addEventListener('change', function() {
                    calcularDiasRestantes();
                });
                
                // Calcular inicialmente si hay valor
                if (fechaVencimiento.value) {
                    calcularDiasRestantes();
                }
            }
            
            // Actualizar barra de progreso para días de recordatorio
            if (diasRecordatorio) {
                diasRecordatorio.addEventListener('input', function() {
                    actualizarProgreso();
                });
                actualizarProgreso();
            }
            
            function calcularDiasRestantes() {
                if (!fechaVencimiento.value) return;
                
                const hoy = new Date();
                const vencimiento = new Date(fechaVencimiento.value);
                const diffTime = vencimiento - hoy;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diasRestantes) {
                    if (diffDays < 0) {
                        diasRestantes.innerHTML = `
                            <span class="text-red-600 font-medium">
                                ⚠ Fecha en el pasado. Selecciona una fecha futura.
                            </span>
                        `;
                    } else if (diffDays === 0) {
                        diasRestantes.innerHTML = `
                            <span class="text-orange-600 font-medium">
                                ⚠ Vence hoy mismo
                            </span>
                        `;
                    } else if (diffDays <= 7) {
                        diasRestantes.innerHTML = `
                            <span class="text-orange-600 font-medium">
                                ⚠ Vence en ${diffDays} día${diffDays !== 1 ? 's' : ''} (próximo)
                            </span>
                        `;
                    } else {
                        diasRestantes.innerHTML = `
                            <span class="text-gray-600">
                                Vence en ${diffDays} días (${vencimiento.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })})
                            </span>
                        `;
                    }
                }
            }
            
            function actualizarProgreso() {
                if (!sliderProgress || !diasRecordatorio) return;
                
                const valor = parseInt(diasRecordatorio.value);
                const porcentaje = (valor / 90) * 100;
                sliderProgress.style.width = `${porcentaje}%`;
            }
        });
        
        // Función para establecer días de recordatorio rápidamente
        function setRecordatorio(dias) {
            const input = document.querySelector('.dias-recordatorio');
            if (input) {
                input.value = dias;
                input.dispatchEvent(new Event('input'));
            }
        }
        
        // Función para limpiar el formulario
        function resetForm() {
            if (confirm('¿Estás seguro de que deseas limpiar todos los campos del formulario?')) {
                document.getElementById('nuevoRegistroForm').reset();
                const diasRestantes = document.getElementById('dias-restantes');
                if (diasRestantes) diasRestantes.innerHTML = '';
            }
        }
        
        // Validación adicional antes de enviar
        document.getElementById('nuevoRegistroForm').addEventListener('submit', function(e) {
            const fechaVencimiento = document.querySelector('.fecha-vencimiento');
            if (fechaVencimiento && fechaVencimiento.value) {
                const hoy = new Date();
                const vencimiento = new Date(fechaVencimiento.value);
                if (vencimiento < hoy) {
                    e.preventDefault();
                    alert('La fecha de vencimiento debe ser una fecha futura.');
                    fechaVencimiento.focus();
                }
            }
        });
    </script>
</x-app-layout>