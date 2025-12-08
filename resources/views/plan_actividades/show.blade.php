<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Principal -->
        <div class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] rounded-2xl p-8 text-white shadow-xl mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white/10 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Actividad {{ $actividad->codigo }}</h1>
                            <p class="text-gray-300 mt-1">Plan de Trabajo TI {{ $actividad->planTrabajo->anio ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Estado y Progreso -->
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                            @if($actividad->progreso == 100) bg-green-100 text-green-800
                            @elseif($actividad->progreso > 0) bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <span class="capitalize">{{ $actividad->estado }}</span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="w-40">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-medium">Progreso</span>
                                    <span>{{ $actividad->progreso }}%</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full
                                        @if($actividad->progreso == 100) bg-green-500
                                        @elseif($actividad->progreso > 0) bg-yellow-500
                                        @else bg-gray-400 @endif"
                                         style="width: {{ $actividad->progreso }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botón Editar -->
                <a href="{{ route('plan-actividad.edit', $actividad->id) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Actividad
                </a>
            </div>
        </div>

        <!-- Información Principal en Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Columna 1: Información Básica -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tarjeta de Información Principal -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-[#0C1C3C]/5 rounded-xl">
                                <svg class="w-6 h-6 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $actividad->actividad }}</h3>
                                <p class="text-gray-600 mt-1">{{ $actividad->seccion }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 mb-2">Objetivo Específico</h4>
                                <p class="text-gray-800">{{ $actividad->objetivo }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 mb-2">Responsable</h4>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#C5A049] to-[#D8B96E] flex items-center justify-center text-[#0C1C3C] font-bold">
                                        {{ strtoupper(substr($actividad->responsable, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $actividad->responsable }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Detalles -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Detalles de la Actividad
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-2">Frecuencia</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-800">{{ $actividad->frecuencia }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-2">Mes Previsto</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-800">{{ $actividad->mes_previsto ?? 'No definido' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-2">Fecha de Ejecución</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-800">{{ $actividad->fecha_ejecucion ?? 'No definida' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-2">Métrica de Éxito</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-gray-800">{{ $actividad->metrica_exito ?? 'No definida' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($actividad->observaciones)
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-500 mb-2">Observaciones</h4>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-gray-800">{{ $actividad->observaciones }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Columna 2: Progreso y Avance -->
            <div class="space-y-8">
                <!-- Tarjeta de Estado Completo -->
                @if($actividad->progreso == 100)
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-green-800 mb-2">Actividad Completada</h3>
                        <p class="text-green-700">
                            Esta actividad alcanzó el 100% de progreso y ya no admite más registros de avance.
                        </p>
                    </div>
                @else
                    <!-- Formulario de Avance -->
                    @role('admin_ti')
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Registrar Avance
                            </h3>
                            
                            <form action="{{ route('actividad.ejecucion.store', $actividad->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="space-y-6">
                                    <!-- Avance -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                                            <span class="flex items-center gap-2">
                                                Incremento de Avance (%)
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="avance" 
                                                   min="1" 
                                                   max="{{ 100 - $actividad->progreso }}"
                                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                                   required>
                                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                                %
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-2">
                                            Progreso actual: <span class="font-semibold">{{ $actividad->progreso }}%</span>
                                            • Disponible: <span class="font-semibold">{{ 100 - $actividad->progreso }}%</span>
                                        </p>
                                    </div>
                                    
                                    <!-- Comentario -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Comentario</label>
                                        <textarea name="comentario" 
                                                  rows="3"
                                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors resize-none"
                                                  placeholder="Describe el avance realizado..."></textarea>
                                    </div>
                                    
                                    <!-- Fecha -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                                            <span class="flex items-center gap-2">
                                                Fecha de Ejecución
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <input type="date" 
                                               name="fecha" 
                                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                               required>
                                    </div>
                                    
                                    <!-- Evidencia -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Evidencia (opcional)</label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#C5A049] transition-colors">
                                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <input type="file" 
                                                   name="evidencia" 
                                                   accept="image/*"
                                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0C1C3C] file:text-white hover:file:bg-[#1A2A4F]">
                                            <p class="text-xs text-gray-500 mt-2">Formatos: JPG, PNG, GIF • Máx. 5MB</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Botón -->
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Guardar Avance
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endrole
                @endif
                
                <!-- Tarjeta de Resumen de Progreso -->
                <div class="bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] rounded-2xl p-8 text-white">
                    <h3 class="text-lg font-bold mb-6">Resumen de Progreso</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Estado Actual</span>
                            <span class="font-bold capitalize">{{ $actividad->estado }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Progreso Total</span>
                            <span class="font-bold text-2xl">{{ $actividad->progreso }}%</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Registros de Avance</span>
                            <span class="font-bold">{{ $actividad->ejecuciones->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Ejecución -->
        @if($actividad->ejecuciones->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                            <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Historial de Ejecución</h2>
                            <p class="text-gray-600">{{ $actividad->ejecuciones->count() }} registros de avance</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($actividad->ejecuciones as $e)
                        <div class="border border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800">
                                            +{{ $e->avance }}% de avance
                                        </span>
                                        <span class="text-gray-600">
                                            {{ $e->fecha ? \Carbon\Carbon::parse($e->fecha)->format('d/m/Y') : 'Sin fecha' }}
                                        </span>
                                    </div>
                                    @if($e->comentario)
                                        <p class="text-gray-700">{{ $e->comentario }}</p>
                                    @endif
                                </div>
                                
                                <div class="flex items-center gap-4">
                                    @if($e->evidencia)
                                        <a href="{{ asset('storage/' . $e->evidencia) }}" target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white text-sm font-medium rounded-xl hover:shadow-md transition-shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver Evidencia
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $e->usuario->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $e->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>