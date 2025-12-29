<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Header Institucional --}}
            <div
                class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 via-navy-800 to-blue-900 p-8 shadow-2xl">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-500/10 to-transparent rounded-full transform translate-x-32 -translate-y-32">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-amber-500/5 to-transparent rounded-full transform -translate-x-32 translate-y-32">
                </div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                                <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                                    Resumen Ejecutivo GOR
                                </h1>
                                <p class="text-blue-200 mt-1">
                                    Revisión de Antecedentes Registrales & Control de Audiencias
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2 text-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Actualizado: {{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Datos Confidenciales</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('gor.antecedentes.index') }}"
                        class="relative group inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 text-white font-semibold transition-all duration-300 hover:scale-[1.02]">
                        <span>Ver Antecedentes</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Filtros Ejecutivos --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Filtros de Análisis</h2>
                    </div>
                    <div class="text-sm text-gray-500">
                        Período de consulta
                    </div>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Circunscripción
                        </label>
                        <select name="circunscripcion"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white shadow-sm">
                            <option value="">Todas las Circunscripciones</option>
                            @foreach ($circunscripciones as $c)
                                <option value="{{ $c }}"
                                    {{ request('circunscripcion') == $c ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Desde
                        </label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Hasta
                        </label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white shadow-sm">
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Aplicar Filtros
                        </button>
                        <a href="{{ route('gor.resumen') }}"
                            class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold border border-gray-300 transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- KPIs Ejecutivos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Antecedentes --}}
                <div
                    class="bg-gradient-to-br from-blue-50 to-white rounded-2xl border border-blue-100 shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-blue-700 uppercase tracking-wider">Total Antecedentes
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAntecedentes) }}
                            </p>
                            <div class="flex items-center gap-2 mt-3">
                                <div class="w-full bg-blue-100 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-100">
                        <p class="text-xs text-gray-600">Registros históricos acumulados</p>
                    </div>
                </div>

                {{-- Total Audiencias --}}
                <div
                    class="bg-gradient-to-br from-navy-50 to-white rounded-2xl border border-navy-100 shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-navy-700 uppercase tracking-wider">Total Audiencias
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAudiencias) }}</p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    {{ $totalAudiencias > 0 ? round(($audAtendidas / $totalAudiencias) * 100, 1) : 0 }}%
                                    atendidas
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-navy-100 rounded-xl">
                            <svg class="w-8 h-8 text-navy-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-navy-100">
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Atendidas: <strong>{{ number_format($audAtendidas) }}</strong></span>
                            <span>Pendientes: <strong>{{ number_format($audPendientes) }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Eficiencia en Atención --}}
                <div
                    class="bg-gradient-to-br from-amber-50 to-white rounded-2xl border border-amber-100 shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-amber-700 uppercase tracking-wider">Eficiencia en
                                Atención</p>
                            <div class="mt-2 space-y-1">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold text-gray-900">{{ $promedioHoras }}h</span>
                                    <span class="text-sm text-gray-600">Promedio</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm">
                                        <span class="text-gray-600">Mediana:</span>
                                        <span class="font-semibold ml-1">{{ $medianaHoras }}h</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">P90:</span>
                                        <span class="font-semibold ml-1">{{ $p90Horas }}h</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-xl">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-amber-100">
                        <p class="text-xs text-gray-600">
                            Pendientes promedio: <strong>{{ $pendientesDiasProm }} días</strong>
                        </p>
                    </div>
                </div>

                {{-- Métricas de Calidad --}}
                <div
                    class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl border border-emerald-100 shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-emerald-700 uppercase tracking-wider">Métricas de
                                Calidad</p>
                            <div class="mt-2">
                                <div class="flex items-center gap-4">
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-gray-900">
                                            @if ($totalAudiencias > 0)
                                                {{ round(($audAtendidas / $totalAudiencias) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">Tasa de atención</div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-xl">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-emerald-100">
                        <p class="text-xs text-gray-600"> Audiencias</p>
                    </div>
                </div>
            </div>

            {{-- Análisis Gráfico --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Tendencia Temporal --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-900">Tendencia por Mes</h2>
                                <p class="text-sm text-gray-600">Últimos 12 meses</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Evolución comparativa
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="trendChart" height="250"></canvas>
                    </div>
                </div>

                {{-- Distribución por Circunscripción --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-navy-100 rounded-lg">
                                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-900">Distribución por Circunscripción</h2>
                                <p class="text-sm text-gray-600">Concentración geográfica</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Volumen de trabajo
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="circChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            {{-- Análisis Estratégico --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Documentos Más Consultados --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            Documentos Más Consultados
                        </h2>
                        <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            Top 5
                        </span>
                    </div>
                    <div class="space-y-4">
                        @forelse($topAsientos as $index => $row)
                            <div
                                class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-50 transition duration-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm font-bold text-blue-700">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-xs">
                                            {{ $row->asiento_tomo_matricula }}
                                        </p>
                                        <p class="text-xs text-gray-500">Documento registral</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">
                                    {{ $row->total }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">Sin datos disponibles</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tipología Documental --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <div class="p-2 bg-navy-100 rounded-lg">
                                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            Tipología Documental
                        </h2>
                        <span class="text-xs font-semibold bg-navy-100 text-navy-700 px-2 py-1 rounded-full">
                            Por tipo de libro
                        </span>
                    </div>
                    <div class="space-y-4">
                        @forelse($topLibros as $index => $row)
                            <div
                                class="flex items-center justify-between p-3 rounded-lg hover:bg-navy-50 transition duration-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-navy-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm font-bold text-navy-700">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $row->tipo_libro }}
                                        </p>
                                        <p class="text-xs text-gray-500">Categoría documental</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-navy-700 bg-navy-100 px-3 py-1 rounded-full">
                                    {{ $row->total }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <p class="mt-2">Sin datos disponibles</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Perfil de Usuarios --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <div class="p-2 bg-amber-100 rounded-lg">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            Perfil de Usuarios
                        </h2>
                        <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-1 rounded-full">
                            Top solicitantes
                        </span>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                Antecedentes
                            </h3>
                            <div class="space-y-3">
                                @forelse($topSolicitantesAnt as $index => $row)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-gray-900 truncate max-w-[150px]">
                                                {{ $row->solicitante_nombre }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">
                                            {{ $row->total }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Sin datos disponibles</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                Audiencias
                            </h3>
                            <div class="space-y-3">
                                @forelse($topSolicitantesAud as $index => $row)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-gray-900 truncate max-w-[150px]">
                                                {{ $row->nombre_solicitante }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-amber-700 bg-amber-100 px-2 py-1 rounded-full">
                                            {{ $row->total }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Sin datos disponibles</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nota Ejecutiva --}}
            <div class="bg-gradient-to-r from-blue-50 to-navy-50 rounded-2xl border border-blue-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-3 bg-blue-100 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Análisis Ejecutivo</h3>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p>• El dashboard presenta métricas clave para la gestión de antecedentes registrales y
                                audiencias.</p>
                            <p>• Los tiempos de atención promedio se mantienen en <strong>{{ $promedioHoras }}
                                    horas</strong>, con una tasa de resolución del
                                @if ($totalAudiencias > 0)
                                    {{ round(($audAtendidas / $totalAudiencias) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                                .
                            </p>
                            <p>• Se recomienda focalizar esfuerzos en las circunscripciones con mayor volumen de
                                trabajo.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ========= Tendencia por mes =========
        const allMonths = (() => {
            const start = new Date();
            start.setDate(1);
            start.setMonth(start.getMonth() - 11);
            const months = [];
            for (let i = 0; i < 12; i++) {
                const y = start.getFullYear();
                const m = String(start.getMonth() + 1).padStart(2, '0');
                months.push(`${y}-${m}`);
                start.setMonth(start.getMonth() + 1);
            }
            return months;
        })();

        const antDataRaw = @json($antPorMes);
        const audDataRaw = @json($audPorMes);

        const antMap = Object.fromEntries(antDataRaw.map(x => [x.mes, Number(x.total)]));
        const audMap = Object.fromEntries(audDataRaw.map(x => [x.mes, Number(x.total)]));

        const antSeries = allMonths.map(m => antMap[m] ?? 0);
        const audSeries = allMonths.map(m => audMap[m] ?? 0);

        // Configuración de colores institucionales
        const institutionalColors = {
            blue: '#1e40af', // Azul corporativo
            navy: '#0f172a', // Azul marino
            gold: '#d97706', // Dorado
            lightBlue: '#3b82f6',
            lightGold: '#f59e0b'
        };

        // Gráfico de tendencia
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: allMonths.map(m => {
                    const [year, month] = m.split('-');
                    return `${month}/${year.substring(2)}`;
                }),
                datasets: [{
                        label: 'Antecedentes',
                        data: antSeries,
                        borderColor: institutionalColors.blue,
                        backgroundColor: institutionalColors.blue + '20',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: institutionalColors.blue,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Audiencias',
                        data: audSeries,
                        borderColor: institutionalColors.gold,
                        backgroundColor: institutionalColors.gold + '20',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: institutionalColors.gold,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: institutionalColors.navy,
                        bodyColor: institutionalColors.navy,
                        borderColor: institutionalColors.blue,
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // ========= Circunscripción =========
        const circRaw = @json($porCircunscripcion);
        const circLabels = circRaw.map(x => x.circunscripcion ?? 'Sin definir');
        const circTotals = circRaw.map(x => Number(x.total));

        // Colores para el gráfico de barras
        const barColors = circTotals.map((_, index) => {
            const colors = [
                institutionalColors.blue,
                institutionalColors.navy,
                institutionalColors.lightBlue,
                '#2563eb',
                '#1d4ed8'
            ];
            return colors[index % colors.length];
        });

        new Chart(document.getElementById('circChart'), {
            type: 'bar',
            data: {
                labels: circLabels,
                datasets: [{
                    label: 'Total de Antecedentes',
                    data: circTotals,
                    backgroundColor: barColors,
                    borderColor: barColors.map(color => color + 'cc'),
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: institutionalColors.navy,
                        bodyColor: institutionalColors.navy,
                        borderColor: institutionalColors.blue,
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `Antecedentes: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Animaciones para los KPIs
        document.addEventListener('DOMContentLoaded', function() {
            const kpiCards = document.querySelectorAll('.bg-gradient-to-br');
            kpiCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Contadores animados (opcional)
            const counters = document.querySelectorAll('.text-3xl');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                if (!isNaN(target)) {
                    let current = 0;
                    const increment = target / 100;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target.toLocaleString();
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current).toLocaleString();
                        }
                    }, 20);
                }
            });
        });
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Mejoras en scroll */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animaciones sutiles */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Gradientes personalizados */
        .from-navy-50 {
            --tw-gradient-from: #f8fafc;
        }

        .to-navy-100 {
            --tw-gradient-to: #f1f5f9;
        }

        .border-navy-100 {
            border-color: #e2e8f0;
        }

        .bg-navy-100 {
            background-color: #e2e8f0;
        }

        .text-navy-600 {
            color: #475569;
        }

        .text-navy-700 {
            color: #334155;
        }

        .bg-navy-50 {
            background-color: #f8fafc;
        }

        /* Efectos de hover mejorados */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Tipografía corporativa */
        h1,
        h2,
        h3,
        h4,
        .font-bold {
            letter-spacing: -0.025em;
        }

        /* Mejoras responsivas */
        @media (max-width: 768px) {
            .text-3xl {
                font-size: 1.875rem;
            }

            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .p-6 {
                padding: 1.25rem;
            }
        }
    </style>
</x-app-layout>
