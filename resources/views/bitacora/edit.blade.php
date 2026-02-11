<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header del formulario -->
        <div class="mb-8">
            <a href="{{ route('bitacora.show', $actividad->id) }}" 
               class="inline-flex items-center gap-2 text-[#0C1C3C] hover:text-[#1A2A4F] font-medium mb-6 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a la actividad
            </a>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C]">Editar Actividad</h1>
                    <p class="text-gray-600 mt-1">Actualiza la información del registro: {{ $actividad->titulo }}</p>
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
                            <h2 class="text-xl font-bold text-white">Información del Incidente</h2>
                            <p class="text-gray-300 text-sm">ID: #{{ str_pad($actividad->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('bitacora.update', $actividad->id) }}" enctype="multipart/form-data" class="p-8">
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

                <!-- Título -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Título
                            <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input type="text" 
                           name="titulo" 
                           value="{{ old('titulo', $actividad->titulo) }}"
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('titulo') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                           placeholder="Ej: Falla en servidor de archivos"
                           required>
                    @error('titulo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Descripción
                        </span>
                    </label>
                    <textarea name="descripcion" 
                              rows="4"
                              class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('descripcion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors resize-none"
                              placeholder="Describe el incidente con detalles...">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid de 2 columnas para catálogos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Tipo de Falla -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Tipo de Falla
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="tipo_falla"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('tipo_falla') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                required>
                            <option value="">Seleccionar tipo</option>
                            @foreach(['Hardware', 'Software', 'Red', 'Impresora', 'Energía', 'Correo', 'Usuario / Permisos', 'Servidor', 'Internet', 'Aplicación Interna', 'Actividad Diaria', 'Pagina Web'] as $item)
                                <option value="{{ $item }}" {{ old('tipo_falla', $actividad->tipo_falla) == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_falla')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Equipo Afectado -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                                Equipo Afectado
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="equipo_afectado"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('equipo_afectado') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                required>
                            <option value="">Seleccionar equipo</option>
                            @foreach(['PC Escritorio', 'Laptop', 'Switch', 'Router', 'Access Point', 'Servidor', 'Impresora', 'UPS', 'Sistema Interno', 'Otro','Actividad Diaria'] as $item)
                                <option value="{{ $item }}" {{ old('equipo_afectado', $actividad->equipo_afectado) == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipo_afectado')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ubicación
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="ubicacion"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('ubicacion') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                required>
                            <option value="">Seleccionar ubicación</option>
                            @foreach(['DE' => 'Dirección Ejecutiva', 'GOR' => 'Gerencia de Operaciones Registrales', 'GAF' => 'Gerencia Administrativa y Financiera', 'GSEA' => 'Gerencia de Servicios Empresariales y Afiliaciones', 'CCISUR' => 'CCISUR', 'CAS' => 'CAS'] as $key => $label)
                                <option value="{{ $key }}" {{ old('ubicacion', $actividad->ubicacion) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('ubicacion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Estado
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="estado"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('estado') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                required>
                            <option value="">Seleccionar estado</option>
                            @foreach(['pendiente' => 'Pendiente', 'en_proceso' => 'En Proceso', 'resuelto' => 'Resuelto'] as $key => $label)
                                <option value="{{ $key }}" {{ old('estado', $actividad->estado) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Grid para fecha y prioridad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="date" 
                               name="fecha" 
                               value="{{ old('fecha', optional($actividad->fecha)->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('fecha') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                               required>
                        @error('fecha')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prioridad -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Prioridad
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="prioridad"
                                class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('prioridad') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                required>
                            <option value="">Seleccionar prioridad</option>
                            @foreach(['baja', 'media', 'alta', 'critica'] as $p)
                                <option value="{{ $p }}" {{ old('prioridad', $actividad->prioridad) == $p ? 'selected' : '' }}>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                        @error('prioridad')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Grid para horas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Hora Inicio -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hora Inicio
                            </span>
                        </label>
                        <input type="time" 
                               name="hora_inicio"
                               value="{{ old('hora_inicio', $actividad->hora_inicio ? \Carbon\Carbon::parse($actividad->hora_inicio)->format('H:i') : '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('hora_inicio') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                        @error('hora_inicio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora Fin -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Hora Fin
                            </span>
                        </label>
                        <input type="time" 
                               name="hora_fin"
                               value="{{ old('hora_fin', $actividad->hora_fin ? \Carbon\Carbon::parse($actividad->hora_fin)->format('H:i') : '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('hora_fin') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                        @error('hora_fin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Solución aplicada -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Solución Aplicada
                        </span>
                    </label>
                    <textarea name="solucion_aplicada" 
                              rows="4"
                              class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('solucion_aplicada') ? 'border-red-300' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors resize-none"
                              placeholder="Describe la solución aplicada al incidente...">{{ old('solucion_aplicada', $actividad->solucion_aplicada) }}</textarea>
                    @error('solucion_aplicada')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Evidencia -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Evidencia (opcional)
                        </span>
                    </label>
                    
                    <!-- Evidencia actual -->
                    @if($actividad->evidencia)
                        <div class="mb-4 bg-gray-50 border border-gray-200 rounded-xl p-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Evidencia Actual</h4>
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . $actividad->evidencia) }}" 
                                     class="h-32 rounded-lg shadow border border-gray-300 object-cover">
                                <div>
                                    <a href="{{ asset('storage/' . $actividad->evidencia) }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white text-sm rounded-xl hover:shadow-md transition-shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver evidencia
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Nueva evidencia -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#C5A049] transition-colors">
                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <input type="file" 
                               name="evidencia" 
                               accept=".png,.jpg,.jpeg,.pdf,.doc,.docx"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0C1C3C] file:text-white hover:file:bg-[#1A2A4F]">
                        <p class="text-xs text-gray-500 mt-2">Formatos: JPG, PNG, GIF • Máx. 5MB</p>
                    </div>
                    @error('evidencia')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Creado:</span> {{ $actividad->created_at->format('d/m/Y H:i') }}
                        <span class="mx-2">•</span>
                        <span class="font-medium">Última actualización:</span> {{ $actividad->updated_at->format('d/m/Y H:i') }}
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('bitacora.show', $actividad->id) }}"
                           class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            Cancelar
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                            <svg class="w-5 h-5 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Actualizar Actividad
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>