<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- ALERTA MODERNA -->
            @if (session('success'))
                <div
                    class="relative bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-4 md:py-3 rounded-2xl shadow-lg overflow-hidden animate-slide-down">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-white/40 to-transparent">
                    </div>
                </div>
            @endif

            <!-- ENCABEZADO MEJORADO -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-xl shadow-md">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                                Control de Audiencias
                            </h1>
                            <p class="text-sm md:text-base text-gray-600 mt-1">
                                Registro y seguimiento de audiencias
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('audiencias.create') }}"
                    class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-medium px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Audiencia
                </a>
            </div>

            <!-- RESUMEN / KPIs MODERNOS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Total -->
                <div
                    class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total de audiencias
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $total }}
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-500">Registros totales del sistema</span>
                    </div>
                </div>

                <!-- Atendidas -->
                <div
                    class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Atendidas</p>
                            <p class="text-3xl font-bold text-green-700 mt-2">
                                {{ $atendidos }}
                            </p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-xl">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-500">
                            @if ($total > 0)
                                {{ round(($atendidos / $total) * 100, 1) }}% del total
                            @else
                                0% del total
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Pendientes -->
                <div
                    class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-amber-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendientes</p>
                            <p class="text-3xl font-bold text-amber-700 mt-2">
                                {{ $pendientes }}
                            </p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-500">
                            @if ($total > 0)
                                {{ round(($pendientes / $total) * 100, 1) }}% del total
                            @else
                                0% del total
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- FILTROS MODERNOS -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtros de Búsqueda
                    </h2>
                </div>

                <form method="GET" class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Solicitante</label>
                            <input type="text" name="nombre" value="{{ request('nombre') }}"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200"
                                placeholder="Nombre del solicitante">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Documento</label>
                            <input type="text" name="documento" value="{{ request('documento') }}"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200"
                                placeholder="Número de documento">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition duration-200">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="atendido" {{ request('estado') == 'atendido' ? 'selected' : '' }}>
                                    Atendido
                                </option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('audiencias.index') }}"
                            class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-2 transition duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Limpiar filtros
                        </a>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- LISTADO MEJORADO -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Registros de Audiencias
                        <span class="text-sm font-normal text-gray-500 ml-2">
                            ({{ $audiencias->total() }} resultados)
                        </span>
                    </h2>

                    @if (request()->hasAny(['nombre', 'documento', 'fecha_desde', 'fecha_hasta', 'estado']))
                        <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            Filtros activos
                        </div>
                    @endif
                </div>

                @forelse ($audiencias as $audiencia)
                    <div
                        class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                        <div class="p-5 md:p-6">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <div class="hidden sm:block">
                                            <div class="p-2 bg-gray-50 rounded-lg">
                                                <svg class="w-6 h-6 text-gray-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ $audiencia->nombre_solicitante }}
                                                </h3>

                                                @if ($audiencia->fecha_hora_atencion)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                                        Atendido
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        <span class="w-2 h-2 bg-amber-500 rounded-full mr-1.5"></span>
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </div>

                                            <div
                                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm text-gray-600 mb-4">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                    </svg>
                                                    <span>Doc:
                                                        <strong>{{ $audiencia->numero_documento }}</strong></span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>Recepción:
                                                        <strong>{{ $audiencia->fecha_recepcion->format('d/m/Y') }}</strong></span>
                                                </div>

                                                @if ($audiencia->fecha_hora_atencion)
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>Atención:
                                                            <strong>{{ $audiencia->fecha_hora_atencion->format('d/m/Y H:i') }}</strong></span>
                                                    </div>
                                                @endif

                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <span>Registrado por:
                                                        <strong>{{ $audiencia->creador->name ?? 'N/D' }}</strong></span>
                                                </div>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded-lg">
                                                <p class="text-gray-700 line-clamp-2">
                                                    {{ $audiencia->motivo }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row md:flex-col gap-2">
                                    <a href="{{ route('audiencias.show', $audiencia->id) }}"
                                        class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-4 py-2.5 rounded-xl transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver detalles
                                    </a>

                                    <a href="{{ route('audiencias.edit', $audiencia->id) }}"
                                        class="inline-flex items-center justify-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium px-4 py-2.5 rounded-xl transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-md p-10 text-center">
                        <div class="max-w-md mx-auto">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros</h3>
                            <p class="text-gray-600 mb-6">
                                @if (request()->hasAny(['nombre', 'documento', 'fecha_desde', 'fecha_hasta', 'estado']))
                                    No se encontraron audiencias con los filtros aplicados.
                                @else
                                    Aún no hay audiencias registradas en el sistema.
                                @endif
                            </p>
                            @if (request()->hasAny(['nombre', 'documento', 'fecha_desde', 'fecha_hasta', 'estado']))
                                <a href="{{ route('audiencias.index') }}"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl transition duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Ver todas las audiencias
                                </a>
                            @else
                                <a href="{{ route('audiencias.create') }}"
                                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-5 py-2.5 rounded-xl transition duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Crear primera audiencia
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- PAGINACIÓN MEJORADA -->
            @if ($audiencias->hasPages())
                <div class="bg-white rounded-2xl shadow-md p-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Mostrando {{ $audiencias->firstItem() ?? 0 }} - {{ $audiencias->lastItem() ?? 0 }} de
                            {{ $audiencias->total() }} registros
                        </div>

                        <div class="flex items-center space-x-1">
                            {{ $audiencias->links('vendor.pagination.tailwind-modern') }}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Estilos personalizados para mejoras adicionales -->
    <style>
        .animate-slide-down {
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Mejoras para el estado de enfoque en formularios */
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Transiciones suaves */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Estilos para la paginación personalizada si es necesario */
        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .pagination .active .page-link {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        @media (max-width: 640px) {
            .text-3xl {
                font-size: 1.875rem;
            }

            .p-5 {
                padding: 1.25rem;
            }

            .rounded-2xl {
                border-radius: 1rem;
            }
        }
    </style>
</x-app-layout>
