<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Encabezado -->
            <div class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] px-8 py-6 text-white">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3 bg-white/10 rounded-xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold">{{ $actividad->titulo }}</h1>
                                <p class="text-gray-300 mt-1">
                                    Registrado por <strong>{{ $actividad->user->name }}</strong> 
                                    el {{ $actividad->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Badges de Estado y Prioridad -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Prioridad -->
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                                @if($actividad->prioridad == 'critica') bg-red-100 text-red-800 border border-red-200
                                @elseif($actividad->prioridad == 'alta') bg-red-50 text-red-700 border border-red-100
                                @elseif($actividad->prioridad == 'media') bg-yellow-100 text-yellow-800 border border-yellow-200
                                @else bg-green-100 text-green-800 border border-green-200 @endif">
                                @if($actividad->prioridad == 'critica')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                @elseif($actividad->prioridad == 'alta')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                @endif
                                {{ ucfirst($actividad->prioridad) }}
                            </span>
                            
                            <!-- Estado -->
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                                @if($actividad->estado == 'resuelto') bg-green-100 text-green-800 border border-green-200
                                @elseif($actividad->estado == 'en_proceso') bg-blue-100 text-blue-800 border border-blue-200
                                @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                @if($actividad->estado == 'resuelto')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($actividad->estado == 'en_proceso')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                @endif
                                {{ ucfirst($actividad->estado) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Botón Editar -->
                    <a href="{{ route('bitacora.edit', $actividad->id) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Actividad
                    </a>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="p-8">
                <!-- Grid de Información Principal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Fecha -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900">Fecha del Incidente</h3>
                        </div>
                        <p class="text-lg text-gray-800">{{ $actividad->fecha->format('d/m/Y') }}</p>
                    </div>

                    <!-- Ubicación -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900">Ubicación</h3>
                        </div>
                        <p class="text-lg text-gray-800">{{ $actividad->ubicacion }}</p>
                    </div>

                    <!-- Equipo Afectado -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900">Equipo Afectado</h3>
                        </div>
                        <p class="text-lg text-gray-800">{{ $actividad->equipo_afectado }}</p>
                    </div>

                    <!-- Tipo de Falla -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900">Tipo de Falla</h3>
                        </div>
                        <p class="text-lg text-gray-800">{{ $actividad->tipo_falla }}</p>
                    </div>

                    <!-- Horario -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hora Inicio -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900">Hora Inicio</h3>
                            </div>
                            <p class="text-lg text-gray-800">
                                {{ $actividad->hora_inicio ? \Carbon\Carbon::parse($actividad->hora_inicio)->format('H:i') : 'No registrada' }}
                            </p>
                        </div>

                        <!-- Hora Fin -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-green-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900">Hora Fin</h3>
                            </div>
                            <p class="text-lg text-gray-800">
                                {{ $actividad->hora_fin ? \Carbon\Carbon::parse($actividad->hora_fin)->format('H:i') : 'No registrada' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                @if($actividad->descripcion)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-gradient-to-r from-[#0C1C3C]/10 to-[#1A2A4F]/10 rounded-xl">
                                <svg class="w-6 h-6 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Descripción del Incidente</h2>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $actividad->descripcion }}</p>
                        </div>
                    </div>
                @endif

                <!-- Solución Aplicada -->
                @if($actividad->solucion_aplicada)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-gradient-to-r from-green-100 to-emerald-100 rounded-xl">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-green-800">Solución Aplicada</h2>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                            <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $actividad->solucion_aplicada }}</p>
                        </div>
                    </div>
                @endif

                <!-- Tiempo Empleado -->
                @if($actividad->tiempo_empleado_minutos)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-xl">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-blue-800">Tiempo Empleado</h2>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-6">
                            <div class="flex items-center gap-3">
                                <div class="text-4xl font-bold text-blue-700">{{ $actividad->tiempo_empleado_minutos }}</div>
                                <div class="text-lg text-blue-600">minutos</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Evidencia -->
                @if($actividad->evidencia)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                                <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Evidencia Adjunta</h2>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $actividad->evidencia) }}" 
                                     class="w-full max-h-96 object-contain rounded-lg shadow-lg border border-gray-300 group-hover:scale-[1.02] transition-transform duration-300"
                                     alt="Evidencia del incidente">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <a href="{{ asset('storage/' . $actividad->evidencia) }}" target="_blank" 
                                       class="bg-white/90 p-3 rounded-full shadow-lg hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m0 0l3-3m-3 3l-3-3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ asset('storage/' . $actividad->evidencia) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white rounded-xl hover:shadow-md transition-shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Descargar Evidencia
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Acciones -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('bitacora.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Bitácora
                    </a>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('bitacora.edit', $actividad->id) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Actividad
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>