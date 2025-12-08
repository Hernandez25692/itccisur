<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header del formulario -->
        <div class="mb-8">
            
            
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C]">Editar Actividad {{ $actividad->codigo }}</h1>
                    
                </div>
            </div>
        </div>

        <!-- Tarjeta del formulario -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Encabezado de la tarjeta -->
            <div class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Información de la Actividad</h2>
                            <p class="text-gray-300 text-sm">Última actualización: {{ $actividad->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Estado actual -->
                    <div class="text-right">
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold
                            @if($actividad->estado == 'finalizado') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200
                            @elseif($actividad->estado == 'en_progreso') bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border border-blue-200
                            @else bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200 @endif">
                            @if($actividad->estado == 'finalizado')
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($actividad->estado == 'en_progreso')
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $actividad->estado)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('plan-actividad.update', $actividad->id) }}" class="p-8">
                @csrf
                @method('PUT')

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
                               value="{{ old('codigo', $actividad->codigo) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('codigo') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: 1.1, 2.3.1"
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
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="seccion" 
                               value="{{ old('seccion', $actividad->seccion) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('seccion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Infraestructura y Seguridad"
                               required>
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
                               value="{{ old('actividad', $actividad->actividad) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('actividad') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Actualización de servidores Windows Server"
                               required>
                        @error('actividad')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Objetivo -->
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
                                  required>{{ old('objetivo', $actividad->objetivo) }}</textarea>
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
                            </span>
                        </label>
                        <input type="text" 
                               name="frecuencia" 
                               value="{{ old('frecuencia', $actividad->frecuencia) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('frecuencia') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Anual, Semestral, Mensual">
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
                            </span>
                        </label>
                        <input type="text" 
                               name="responsable" 
                               value="{{ old('responsable', $actividad->responsable) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('responsable') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: Juan Pérez, María García">
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
                            <option value="Enero" {{ old('mes_previsto', $actividad->mes_previsto) == 'Enero' ? 'selected' : '' }}>Enero</option>
                            <option value="Febrero" {{ old('mes_previsto', $actividad->mes_previsto) == 'Febrero' ? 'selected' : '' }}>Febrero</option>
                            <option value="Marzo" {{ old('mes_previsto', $actividad->mes_previsto) == 'Marzo' ? 'selected' : '' }}>Marzo</option>
                            <option value="Abril" {{ old('mes_previsto', $actividad->mes_previsto) == 'Abril' ? 'selected' : '' }}>Abril</option>
                            <option value="Mayo" {{ old('mes_previsto', $actividad->mes_previsto) == 'Mayo' ? 'selected' : '' }}>Mayo</option>
                            <option value="Junio" {{ old('mes_previsto', $actividad->mes_previsto) == 'Junio' ? 'selected' : '' }}>Junio</option>
                            <option value="Julio" {{ old('mes_previsto', $actividad->mes_previsto) == 'Julio' ? 'selected' : '' }}>Julio</option>
                            <option value="Agosto" {{ old('mes_previsto', $actividad->mes_previsto) == 'Agosto' ? 'selected' : '' }}>Agosto</option>
                            <option value="Septiembre" {{ old('mes_previsto', $actividad->mes_previsto) == 'Septiembre' ? 'selected' : '' }}>Septiembre</option>
                            <option value="Octubre" {{ old('mes_previsto', $actividad->mes_previsto) == 'Octubre' ? 'selected' : '' }}>Octubre</option>
                            <option value="Noviembre" {{ old('mes_previsto', $actividad->mes_previsto) == 'Noviembre' ? 'selected' : '' }}>Noviembre</option>
                            <option value="Diciembre" {{ old('mes_previsto', $actividad->mes_previsto) == 'Diciembre' ? 'selected' : '' }}>Diciembre</option>
                        </select>
                        @error('mes_previsto')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de ejecución -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha de ejecución
                            </span>
                        </label>
                        <input type="text" 
                               name="fecha_ejecucion" 
                               value="{{ old('fecha_ejecucion', $actividad->fecha_ejecucion) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('fecha_ejecucion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               placeholder="Ej: 20–29/10/2026, Primer trimestre">
                        @error('fecha_ejecucion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Métrica de éxito -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Métrica de éxito
                            </span>
                        </label>
                        <input type="text" 
                               name="metrica_exito" 
                               value="{{ old('metrica_exito', $actividad->metrica_exito) }}"
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
                                  placeholder="Agrega observaciones, consideraciones o dependencias importantes...">{{ old('observaciones', $actividad->observaciones) }}</textarea>
                        @error('observaciones')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Estado
                            </span>
                        </label>
                        <select name="estado"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('estado') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                            <option value="pendiente" {{ old('estado', $actividad->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_progreso" {{ old('estado', $actividad->estado) == 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                            <option value="finalizado" {{ old('estado', $actividad->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        @error('estado')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                   
                    
                    <div class="flex items-center gap-4">
                        
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                            <svg class="w-5 h-5 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>