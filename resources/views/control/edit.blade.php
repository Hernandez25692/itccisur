<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <!-- Header del formulario -->
        <div class="mb-8">
            <a href="{{ route('control.index') }}"
                class="inline-flex items-center gap-2 text-[#0C1C3C] hover:text-[#1A2A4F] font-medium mb-4 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver al Control TI
            </a>

            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C]">Editar Registro de Control TI</h1>
                    <p class="text-gray-600 mt-1">Actualiza la información del registro: {{ $recordatorio->actividad }}
                    </p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Información del Registro</h2>
                            <p class="text-gray-300 text-sm">ID: #{{ str_pad($recordatorio->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                    </div>

                    <!-- Estado actual -->
                    @php
                        $hoy = now();
                        $vence = \Carbon\Carbon::parse($recordatorio->fecha_vencimiento);
                        $dias = $hoy->diffInDays($vence, false);
                    @endphp

                    <div class="text-right">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold 
                            @if ($recordatorio->atendido) bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200
                            @elseif($dias < 0)
                                bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200
                            @elseif($dias <= 3)
                                bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 border border-orange-200
                            @else
                                bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700 border border-gray-200 @endif">
                            @if ($recordatorio->atendido)
                                ✔ Atendido
                            @elseif($dias < 0)
                                ⚠ Vencido
                            @elseif($dias <= 3)
                                ⚠ Urgente ({{ (int)$dias }} días)
                            @else
                                Vigente ({{ (int)$dias }} días)
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('control.update', $recordatorio->id) }}" class="p-8">
                @csrf
                @method('PUT')

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
                        <input type="text" name="actividad" value="{{ old('actividad', $recordatorio->actividad) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors placeholder-gray-400"
                            placeholder="Ej: Renovación de licencia Office 365" required>
                        @error('actividad')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Tipo</label>
                        <select name="tipo"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                            <option value="">Seleccionar tipo</option>
                            <option value="licencia"
                                {{ old('tipo', $recordatorio->tipo) == 'licencia' ? 'selected' : '' }}>Licencia</option>
                            <option value="dominio"
                                {{ old('tipo', $recordatorio->tipo) == 'dominio' ? 'selected' : '' }}>Dominio</option>
                            <option value="servicio"
                                {{ old('tipo', $recordatorio->tipo) == 'servicio' ? 'selected' : '' }}>Servicio
                            </option>
                            <option value="certificado"
                                {{ old('tipo', $recordatorio->tipo) == 'certificado' ? 'selected' : '' }}>Certificado
                            </option>
                            <option value="hardware"
                                {{ old('tipo', $recordatorio->tipo) == 'hardware' ? 'selected' : '' }}>Hardware
                            </option>
                            <option value="software"
                                {{ old('tipo', $recordatorio->tipo) == 'software' ? 'selected' : '' }}>Software
                            </option>
                            <option value="otros" {{ old('tipo', $recordatorio->tipo) == 'otros' ? 'selected' : '' }}>
                                Otros</option>
                        </select>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Descripción
                        </span>
                    </label>
                    <textarea name="descripcion" rows="3"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors placeholder-gray-400 resize-none"
                        placeholder="Describe detalles importantes sobre este registro...">{{ old('descripcion', $recordatorio->descripcion) }}</textarea>
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
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Fecha de Ejecución
                            </span>
                        </label>
                        <div class="relative">
                            <input type="date" name="fecha_ejecucion"
                                value="{{ $recordatorio->fecha_ejecucion ? \Carbon\Carbon::parse($recordatorio->fecha_ejecucion)->format('Y-m-d') : '' }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                        </div>
                        @if ($recordatorio->fecha_ejecucion)
                            <p class="mt-2 text-sm text-gray-500">
                                Fecha actual:
                                {{ \Carbon\Carbon::parse($recordatorio->fecha_ejecucion)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>

                    <!-- Fecha de Vencimiento -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fecha de Vencimiento
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="date" name="fecha_vencimiento"
                                value="{{ $recordatorio->fecha_vencimiento ? \Carbon\Carbon::parse($recordatorio->fecha_vencimiento)->format('Y-m-d') : '' }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                required>
                        </div>
                        @error('fecha_vencimiento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                    </div>
                </div>

                <!-- Configuración de Recordatorio -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
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
                                <input type="number" name="dias_recordatorio"
                                    value="{{ old('dias_recordatorio', $recordatorio->dias_recordatorio) }}"
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors"
                                    min="1" max="90" required>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    días antes
                                </div>
                            </div>
                            @error('dias_recordatorio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="space-y-6">
                            <!-- Notificar -->
                            <div
                                class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="font-medium text-gray-900">¿Notificar?</label>
                                        <p class="text-sm text-gray-500">Recibir alertas por vencimiento</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notificar" value="1"
                                        {{ $recordatorio->notificar ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C5A049]">
                                    </div>
                                </label>
                            </div>

                            <!-- Atendido -->
                            <div
                                class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-50 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="font-medium text-gray-900">¿Atendido?</label>
                                        <p class="text-sm text-gray-500">Marcar como completado</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="atendido" value="1"
                                        {{ $recordatorio->atendido ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        Última actualización: {{ $recordatorio->updated_at->format('d/m/Y H:i') }}
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('control.index') }}"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                            <svg class="w-5 h-5 transform group-hover:rotate-12 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Actualizar Registro
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Campo requerido</p>
                        <p class="text-xs text-blue-700">Los campos marcados con * son obligatorios</p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-amber-900">Recordatorio activo</p>
                        <p class="text-xs text-amber-700">Alertas
                            {{ $recordatorio->notificar ? 'activadas' : 'desactivadas' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Actualización segura</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</x-app-layout>
