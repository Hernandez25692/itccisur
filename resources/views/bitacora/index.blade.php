<x-app-layout>
    <!-- Header de la página -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bitácora de Actividades</h1>
                <p class="text-gray-600 mt-2">Registro y seguimiento de incidentes y mantenimientos del sistema</p>
            </div>

            @role('admin_ti')
                <a href="{{ route('bitacora.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Registrar Actividad</span>
                </a>
            @endrole
        </div>

        <!-- Indicadores de estado -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] text-white p-4 rounded-xl shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300">Total Actividades</p>
                        <p class="text-2xl font-bold mt-1">{{ $actividades->total() }}</p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#1A2A4F] to-[#2A3A6F] text-white p-4 rounded-xl shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300">Pendientes</p>
                        <p class="text-2xl font-bold mt-1">
                            {{ $actividades->where('estado', 'pendiente')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#1A2A4F] to-[#2A3A6F] text-white p-4 rounded-xl shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300">En Proceso</p>
                        <p class="text-2xl font-bold mt-1">
                            {{ $actividades->where('estado', 'en_proceso')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#1A2A4F] to-[#2A3A6F] text-white p-4 rounded-xl shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300">Resueltas</p>
                        <p class="text-2xl font-bold mt-1">
                            {{ $actividades->where('estado', 'resuelto')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filtros de Búsqueda</h3>
            <span class="text-sm text-gray-500">{{ $actividades->total() }} registros encontrados</span>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                <select name="prioridad"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                    <option value="todas" class="py-2">Todas las prioridades</option>
                    <option value="baja" class="py-2">Baja</option>
                    <option value="media" class="py-2">Media</option>
                    <option value="alta" class="py-2">Alta</option>
                    <option value="critica" class="py-2">Crítica</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
                    <option value="todos" class="py-2">Todos los estados</option>
                    <option value="pendiente" class="py-2">Pendiente</option>
                    <option value="en_proceso" class="py-2">En Proceso</option>
                    <option value="resuelto" class="py-2">Resuelto</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                <input type="date" name="desde"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                <input type="date" name="hasta"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049] transition-colors">
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white font-medium px-6 py-3 rounded-xl shadow hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Filtrar</span>
                </button>
            </div>
        </form>

        @if (request()->anyFilled(['prioridad', 'estado', 'desde', 'hasta']))
            <div class="mt-4 flex items-center gap-2 text-sm">
                <span class="text-gray-600">Filtros aplicados:</span>
                <div class="flex flex-wrap gap-2">
                    @if (request('prioridad') && request('prioridad') != 'todas')
                        <span
                            class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            Prioridad: {{ ucfirst(request('prioridad')) }}
                            <a href="{{ request()->fullUrlWithQuery(['prioridad' => null]) }}"
                                class="text-blue-600 hover:text-blue-800">
                                ×
                            </a>
                        </span>
                    @endif
                    @if (request('desde'))
                        <span
                            class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">
                            Desde: {{ request('desde') }}
                            <a href="{{ request()->fullUrlWithQuery(['desde' => null]) }}"
                                class="text-green-600 hover:text-green-800">
                                ×
                            </a>
                        </span>
                    @endif
                </div>
                <a href="{{ route('bitacora.index') }}"
                    class="ml-auto text-sm text-[#C5A049] hover:text-[#D8B96E] font-medium">
                    Limpiar filtros
                </a>
            </div>
        @endif
    </div>

    <!-- Lista de Actividades -->
    <div class="space-y-4">
        @forelse ($actividades as $a)
            <div
                class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Barra de prioridad -->
                <div
                    class="h-2 
                    @if ($a->prioridad == 'critica') bg-gradient-to-r from-red-600 to-red-800
                    @elseif($a->prioridad == 'alta') bg-gradient-to-r from-red-400 to-red-600
                    @elseif($a->prioridad == 'media') bg-gradient-to-r from-yellow-400 to-yellow-600
                    @else bg-gradient-to-r from-green-400 to-green-600 @endif">
                </div>

                <div class="p-6">
                    <!-- Header de la actividad -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($a->prioridad == 'critica') bg-red-100 text-red-800
                                    @elseif($a->prioridad == 'alta') bg-red-50 text-red-700
                                    @elseif($a->prioridad == 'media') bg-yellow-50 text-yellow-700
                                    @else bg-green-50 text-green-700 @endif">
                                    {{ strtoupper($a->prioridad) }}
                                </span>

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if ($a->estado == 'pendiente') bg-gray-100 text-gray-800
                                    @elseif($a->estado == 'en_proceso') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $a->estado)) }}
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mt-3">{{ $a->titulo }}</h3>
                        </div>

                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $a->fecha->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $a->hora_inicio }}
                                @if ($a->hora_fin)
                                    - {{ $a->hora_fin }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <p class="text-gray-700 mb-6 leading-relaxed">{{ $a->descripcion }}</p>

                    <!-- Información detallada -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Equipo</p>
                                <p class="font-medium text-gray-900">{{ $a->equipo_afectado }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ubicación</p>
                                <p class="font-medium text-gray-900">{{ $a->ubicacion }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-[#0C1C3C]/5 rounded-lg">
                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Registrado por</p>
                                <p class="font-medium text-gray-900">{{ $a->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Solución aplicada -->
                    @if ($a->solucion_aplicada)
                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium text-green-800">Solución Aplicada</span>
                            </div>
                            <p class="text-green-700">{{ $a->solucion_aplicada }}</p>
                        </div>
                    @endif

                    <!-- Evidencia -->
                    @if ($a->evidencia)
                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-700 mb-3">Evidencia Adjunta</p>
                            <div class="inline-block relative group">
                                @php
                                    $evidenciaPath = 'storage/' . $a->evidencia;
                                    $extension = strtolower(pathinfo($a->evidencia, PATHINFO_EXTENSION));
                                    $isPdf = $extension === 'pdf';
                                @endphp

                                @if ($isPdf)
                                    <div
                                        class="h-40 w-32 rounded-lg shadow-md border border-gray-200 bg-gray-100 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                        <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M7 2H5a2 2 0 00-2 2v16a2 2 0 002 2h14a2 2 0 002-2V9l-5-5H7z" />
                                            <text x="50%" y="50%" text-anchor="middle" dy=".3em"
                                                class="text-xs font-bold fill-red-600">PDF</text>
                                        </svg>
                                    </div>
                                @else
                                    <img src="{{ asset($evidenciaPath) }}" alt="Evidencia de actividad"
                                        class="h-40 rounded-lg shadow-md border border-gray-200 object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif

                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <a href="{{ asset($evidenciaPath) }}" target="_blank"
                                        class="bg-white/90 text-gray-900 p-2 rounded-full shadow-lg hover:bg-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-4-6l6-6m0 0l-6 6m6-6v12">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="text-sm text-gray-500">
                            <span class="font-medium">Última actualización:</span>
                            {{ $a->updated_at->diffForHumans() }}
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('bitacora.show', $a->id) }}"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white font-medium px-5 py-2 rounded-xl shadow hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Ver Detalles
                            </a>

                            @role('admin_ti')
                                <a href="{{ route('bitacora.edit', $a->id) }}"
                                    class="inline-flex items-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-medium px-5 py-2 rounded-xl shadow hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Estado vacío -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay actividades registradas</h3>
                    <p class="text-gray-600 mb-6">Comienza registrando tu primera actividad en el sistema.</p>
                    @role('admin_ti')
                        <a href="{{ route('bitacora.create') }}"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            <span>Crear primera actividad</span>
                        </a>
                    @endrole
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if ($actividades->hasPages())
        <div class="mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $actividades->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $actividades->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $actividades->total() }}</span>
                    resultados
                </div>

                <div class="flex items-center space-x-2">
                    {{ $actividades->onEachSide(1)->links() }}

                </div>
            </div>
        </div>
    @endif

    <!-- Modal de confirmación (para futuras implementaciones) -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">¿Confirmar eliminación?</h3>
            <p class="text-gray-600 mb-6">Esta acción no se puede deshacer. La actividad será eliminada
                permanentemente.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Componente de paginación personalizado (opcional) -->
@if (!view()->exists('vendor.pagination.custom'))
    @php
        // Crear una vista simple para la paginación personalizada
    @endphp
@endif
