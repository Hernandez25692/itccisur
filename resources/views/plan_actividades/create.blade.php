<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header del formulario -->
        <div class="mb-8">
            <a href="{{ route('plan-trabajo.show', $plan->id) }}" 
               class="inline-flex items-center gap-2 text-[#0C1C3C] hover:text-[#1A2A4F] font-medium mb-6 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Plan {{ $plan->anio }}
            </a>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-gradient-to-br from-[#C5A049] to-[#D8B96E] rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C]">Nueva Actividad</h1>
                    <p class="text-gray-600 mt-1">Agrega una nueva actividad al Plan de Trabajo TI {{ $plan->anio }}</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-[#0C1C3C]">Información de la Actividad</h2>
                        <p class="text-[#0C1C3C]/80 text-sm">Completa los campos para agregar la nueva actividad</p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('plan-actividad.store', $plan->id) }}" method="POST" class="p-8">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Código -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                Código
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="codigo" 
                               value="{{ old('codigo') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('codigo') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: 1.1, 2.3.1, A.2"
                               required>
                        @error('codigo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sección -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                Sección
                            </span>
                        </label>
                        <select name="seccion"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('seccion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                            <option value="">Seleccionar sección</option>
                            <option value="Infraestructura y Seguridad" {{ old('seccion') == 'Infraestructura y Seguridad' ? 'selected' : '' }}>Infraestructura y Seguridad</option>
                            <option value="Redes y Comunicaciones" {{ old('seccion') == 'Redes y Comunicaciones' ? 'selected' : '' }}>Redes y Comunicaciones</option>
                            <option value="Soporte Técnico" {{ old('seccion') == 'Soporte Técnico' ? 'selected' : '' }}>Soporte Técnico</option>
                            <option value="Seguridad Informática" {{ old('seccion') == 'Seguridad Informática' ? 'selected' : '' }}>Seguridad Informática</option>
                            <option value="Sistemas y Desarrollo" {{ old('seccion') == 'Sistemas y Desarrollo' ? 'selected' : '' }}>Sistemas y Desarrollo</option>
                        </select>
                        @error('seccion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actividad -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Actividad
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="actividad" 
                               value="{{ old('actividad') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('actividad') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Actualización de servidores Windows Server"
                               required>
                        @error('actividad')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Objetivo Específico -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Objetivo Específico
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <textarea name="objetivo" 
                                  rows="3"
                                  class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('objetivo') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors resize-none"
                                  placeholder="Describe el objetivo específico de esta actividad..."
                                  required>{{ old('objetivo') }}</textarea>
                        @error('objetivo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Frecuencia -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Frecuencia
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="frecuencia" 
                               value="{{ old('frecuencia') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('frecuencia') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Anual, Semestral, Mensual, Trimestral"
                               required>
                        @error('frecuencia')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsable -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Responsable
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="responsable" 
                               value="{{ old('responsable') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('responsable') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Juan Pérez, María García"
                               required>
                        @error('responsable')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mes previsto -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Mes previsto
                            </span>
                        </label>
                        <select name="mes_previsto"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('mes_previsto') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                            <option value="">Seleccionar mes</option>
                            <option value="Enero" {{ old('mes_previsto') == 'Enero' ? 'selected' : '' }}>Enero</option>
                            <option value="Febrero" {{ old('mes_previsto') == 'Febrero' ? 'selected' : '' }}>Febrero</option>
                            <option value="Marzo" {{ old('mes_previsto') == 'Marzo' ? 'selected' : '' }}>Marzo</option>
                            <option value="Abril" {{ old('mes_previsto') == 'Abril' ? 'selected' : '' }}>Abril</option>
                            <option value="Mayo" {{ old('mes_previsto') == 'Mayo' ? 'selected' : '' }}>Mayo</option>
                            <option value="Junio" {{ old('mes_previsto') == 'Junio' ? 'selected' : '' }}>Junio</option>
                            <option value="Julio" {{ old('mes_previsto') == 'Julio' ? 'selected' : '' }}>Julio</option>
                            <option value="Agosto" {{ old('mes_previsto') == 'Agosto' ? 'selected' : '' }}>Agosto</option>
                            <option value="Septiembre" {{ old('mes_previsto') == 'Septiembre' ? 'selected' : '' }}>Septiembre</option>
                            <option value="Octubre" {{ old('mes_previsto') == 'Octubre' ? 'selected' : '' }}>Octubre</option>
                            <option value="Noviembre" {{ old('mes_previsto') == 'Noviembre' ? 'selected' : '' }}>Noviembre</option>
                            <option value="Diciembre" {{ old('mes_previsto') == 'Diciembre' ? 'selected' : '' }}>Diciembre</option>
                        </select>
                        @error('mes_previsto')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha a ejecutar -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha a ejecutar
                            </span>
                        </label>
                        <input type="text" 
                               name="fecha_ejecucion" 
                               value="{{ old('fecha_ejecucion') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('fecha_ejecucion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: 20–29/10/2026, Primer trimestre, Q3">
                        @error('fecha_ejecucion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Métrica de Éxito -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Métrica de Éxito
                            </span>
                        </label>
                        <input type="text" 
                               name="metrica_exito" 
                               value="{{ old('metrica_exito') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('metrica_exito') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: 100% de servidores actualizados, 0 incidentes críticos">
                        @error('metrica_exito')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Observaciones
                            </span>
                        </label>
                        <textarea name="observaciones" 
                                  rows="2"
                                  class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('observaciones') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors resize-none"
                                  placeholder="Agrega observaciones, consideraciones o dependencias importantes...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Plan:</span> {{ $plan->anio }} • 
                        <span class="font-medium">Estado:</span> {{ ucfirst($plan->estado) }}
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
                            Guardar Actividad
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
                Recomendaciones para crear actividades
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Código estructurado</h4>
                        <p class="text-sm text-gray-600">Usa un sistema de numeración jerárquico como 1.1, 1.2, 2.1.1 para organizar las actividades.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Objetivos medibles</h4>
                        <p class="text-sm text-gray-600">Define objetivos específicos y métricas claras para evaluar el éxito de cada actividad.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para mejoras de UX -->
    <script>
        // Función para limpiar el formulario
        function resetForm() {
            if (confirm('¿Estás seguro de que deseas limpiar todos los campos del formulario?')) {
                document.querySelector('form').reset();
            }
        }

        // Sugerencias para frecuencia
        const frecuenciaInput = document.querySelector('input[name="frecuencia"]');
        if (frecuenciaInput) {
            frecuenciaInput.addEventListener('focus', function() {
                const suggestions = ['Anual', 'Semestral', 'Trimestral', 'Mensual', 'Semanal', 'Única vez', 'Según demanda'];
                this.setAttribute('list', 'frecuencia-suggestions');
                
                if (!document.getElementById('frecuencia-suggestions')) {
                    const datalist = document.createElement('datalist');
                    datalist.id = 'frecuencia-suggestions';
                    suggestions.forEach(suggestion => {
                        const option = document.createElement('option');
                        option.value = suggestion;
                        datalist.appendChild(option);
                    });
                    document.body.appendChild(datalist);
                }
            });
        }

        // Sugerencias para responsables
        const responsableInput = document.querySelector('input[name="responsable"]');
        if (responsableInput) {
            responsableInput.addEventListener('focus', function() {
                this.setAttribute('list', 'responsable-suggestions');
                
                if (!document.getElementById('responsable-suggestions')) {
                    const datalist = document.createElement('datalist');
                    datalist.id = 'responsable-suggestions';
                    const suggestions = [
                        'Equipo de Infraestructura',
                        'Equipo de Soporte',
                        'Administrador de Sistemas',
                        'Especialista en Seguridad',
                        'Coordinador TI'
                    ];
                    
                    suggestions.forEach(suggestion => {
                        const option = document.createElement('option');
                        option.value = suggestion;
                        datalist.appendChild(option);
                    });
                    document.body.appendChild(datalist);
                }
            });
        }
    </script>
</x-app-layout>