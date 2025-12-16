<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section - Rediseñado -->
            <div class="mb-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    Calendario Editorial
                                </h1>
                                <p class="text-gray-600 mt-1 text-sm sm:text-base">
                                    Gestión de publicaciones para redes sociales institucionales
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas Rápidas -->
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[140px] bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $registros->count() }}</p>
                                </div>
                                <div class="p-2 bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        @role('admin_ti|calendario')
                            <div>
                                <a href="{{ route('calendario-editorial.create') }}"
                                    class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Nueva Publicación</span>
                                </a>
                            </div>
                        @endrole
                    </div>
                </div>

                <!-- Filtros y Estados -->
                <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Estado de Publicaciones</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach ([['color' => 'green', 'label' => 'Publicado', 'count' => $registros->where('estado', 'publicado')->count()], ['color' => 'orange', 'label' => 'Pendiente', 'count' => $registros->where('estado', 'pendiente')->count()], ['color' => 'yellow', 'label' => 'Reprogramado', 'count' => $registros->where('estado', 'reprogramado')->count()], ['color' => 'red', 'label' => 'Cancelado', 'count' => $registros->where('estado', 'cancelado')->count()]] as $status)
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded-full bg-{{ $status['color'] }}-500"></span>
                                            <span
                                                class="text-sm font-medium text-gray-700">{{ $status['label'] }}</span>
                                        </div>
                                        <span
                                            class="text-xs font-semibold bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full">
                                            {{ $status['count'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!-- Filtros de Búsqueda Mejorados -->
            <form method="GET" class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">



                    <!-- Estado -->
                    <select name="estado" class="border rounded-lg p-2 text-sm">
                        <option value="">Todos los estados</option>
                        @foreach (['publicado', 'pendiente', 'reprogramado', 'cancelado'] as $e)
                            <option value="{{ $e }}" @selected(request('estado') == $e)>
                                {{ ucfirst($e) }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Semana -->
                    <input type="number" name="semana" value="{{ request('semana') }}" placeholder="Semana"
                        class="border rounded-lg p-2 text-sm">

                    <!-- Mes -->
                    <!-- Mes -->
                    <select name="mes" class="border rounded-lg p-2 text-sm">
                        <option value="">Todos los meses</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected(request('mes') == $m)>
                                {{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <button class="flex-1 bg-blue-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-blue-700">
                            🔍 Filtrar
                        </button>

                        <a href="{{ route('calendario-editorial.index') }}"
                            class="flex-1 text-center bg-gray-200 text-gray-700 rounded-lg px-4 py-2 text-sm hover:bg-gray-300">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <!-- Desktop Table View - Mejorado -->
            <div class="hidden lg:block">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        <div class="flex items-center justify-between gap-2 cursor-pointer group"
                                            onclick="toggleSort('semana')">
                                            <span>Semana</span>
                                            <div class="flex flex-col gap-0.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5z"></path>
                                                </svg>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        <div class="flex items-center justify-between gap-2 cursor-pointer group"
                                            onclick="toggleSort('fecha')">
                                            <span>Fecha y Hora</span>
                                            <div class="flex flex-col gap-0.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5z"></path>
                                                </svg>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        <div class="flex items-center justify-between gap-2 cursor-pointer group"
                                            onclick="toggleSort('contenido')">
                                            <span>Contenido</span>
                                            <div class="flex flex-col gap-0.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5z"></path>
                                                </svg>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        <div class="flex items-center justify-between gap-2 cursor-pointer group"
                                            onclick="toggleSort('estado')">
                                            <span>Estado</span>
                                            <div class="flex flex-col gap-0.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5z"></path>
                                                </svg>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100" id="tableBody">
                                @forelse ($registros as $item)
                                    @php
                                        $estadoConfig = match ($item->estado) {
                                            'publicado' => [
                                                'bg' => 'bg-gradient-to-r from-green-50 to-emerald-50',
                                                'text' => 'text-green-800',
                                                'badge' =>
                                                    'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                                'dot' => 'bg-green-500',
                                                'border' => 'border-green-200',
                                            ],
                                            'pendiente' => [
                                                'bg' => 'bg-gradient-to-r from-orange-50 to-amber-50',
                                                'text' => 'text-orange-800',
                                                'badge' =>
                                                    'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 border border-orange-200',
                                                'dot' => 'bg-orange-500',
                                                'border' => 'border-orange-200',
                                            ],
                                            'reprogramado' => [
                                                'bg' => 'bg-gradient-to-r from-yellow-50 to-amber-50',
                                                'text' => 'text-yellow-800',
                                                'badge' =>
                                                    'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
                                                'dot' => 'bg-yellow-500',
                                                'border' => 'border-yellow-200',
                                            ],
                                            'cancelado' => [
                                                'bg' => 'bg-gradient-to-r from-red-50 to-rose-50',
                                                'text' => 'text-red-800',
                                                'badge' =>
                                                    'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
                                                'dot' => 'bg-red-500',
                                                'border' => 'border-red-200',
                                            ],
                                            default => [
                                                'bg' => 'bg-gradient-to-r from-gray-50 to-gray-100',
                                                'text' => 'text-gray-800',
                                                'badge' =>
                                                    'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-200',
                                                'dot' => 'bg-gray-500',
                                                'border' => 'border-gray-200',
                                            ],
                                        };
                                    @endphp

                                    <tr class="hover:bg-gray-50/50 transition-all duration-200 group {{ $estadoConfig['bg'] }}"
                                        style="border-left: 4px solid {{ $estadoConfig['dot'] == 'bg-green-500' ? '#10B981' : ($estadoConfig['dot'] == 'bg-orange-500' ? '#F97316' : ($estadoConfig['dot'] == 'bg-yellow-500' ? '#EAB308' : '#EF4444')) }};"
                                        data-semana="{{ $item->semana }}"
                                        data-fecha="{{ $item->fecha_publicacion->format('Y-m-d') }}"
                                        data-contenido="{{ strtolower($item->tema) }}"
                                        data-estado="{{ strtolower($item->estado) }}">

                                        <!-- Semana -->
                                        <td class="px-8 py-6 align-top border-r {{ $estadoConfig['border'] }}">
                                            <div class="flex flex-col items-center justify-center">
                                                <span
                                                    class="text-2xl font-bold text-gray-900">{{ $item->semana }}</span>
                                                <span class="text-xs text-gray-500 mt-1">SEMANA</span>
                                            </div>
                                        </td>

                                        <!-- Fecha y Hora -->
                                        <td class="px-8 py-6 align-top border-r {{ $estadoConfig['border'] }}">
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-gray-900">
                                                        {{ $item->fecha_publicacion->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 bg-white/50 rounded-lg px-2 py-1 inline-block">
                                                    {{ $item->dia }}
                                                </div>
                                                <div class="flex items-center gap-2 mt-3">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-base font-bold text-gray-900">
                                                        {{ $item->hora }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contenido Principal -->
                                        <td class="px-8 py-6 align-top border-r {{ $estadoConfig['border'] }}">
                                            <div class="space-y-4">
                                                <!-- Tema -->
                                                <div>
                                                    <h3
                                                        class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-700 transition-colors">
                                                        {{ $item->tema }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                                                        {{ $item->encabezado }}
                                                    </p>
                                                </div>

                                                <!-- Metadata -->
                                                <div class="space-y-3">
                                                    <!-- Área -->
                                                    @if ($item->area)
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-xs font-medium text-gray-500">Área:</span>
                                                            <span
                                                                class="px-3 py-1 bg-white/70 text-gray-700 rounded-full text-xs font-medium border border-gray-200">
                                                                {{ $item->area }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <!-- Redes Sociales -->
                                                    @if (!empty($item->publicar_en))
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-xs font-medium text-gray-500">Redes:</span>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach ($item->publicar_en as $red)
                                                                    <span
                                                                        class="px-2.5 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 rounded-lg text-xs font-medium border border-blue-100 flex items-center gap-1">
                                                                        <svg class="w-3 h-3" fill="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path
                                                                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                                                        </svg>
                                                                        {{ $red }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Enlace -->
                                                    @if ($item->enlace)
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ $item->enlace }}" target="_blank"
                                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors group/link">
                                                                <span>Ver publicación</span>
                                                                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Estado -->
                                        <td class="px-8 py-6 align-top border-r {{ $estadoConfig['border'] }}">
                                            <div class="flex flex-col items-center gap-3">
                                                <span
                                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $estadoConfig['badge'] }} capitalize shadow-sm">
                                                    <span
                                                        class="w-2 h-2 rounded-full {{ $estadoConfig['dot'] }} mr-2 animate-pulse"></span>
                                                    {{ $item->estado }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Acciones -->
                                        <td class="px-8 py-6 align-top">
                                            <div class="flex flex-col gap-3 min-w-[140px]">
                                                <!-- Ver -->
                                                <a href="{{ route('calendario-editorial.show', $item) }}"
                                                    class="group/btn inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 hover:shadow-sm hover:border-gray-400">
                                                    <svg class="w-4 h-4 text-gray-600 group-hover/btn:text-blue-600 transition-colors"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    <span>Detalles</span>
                                                </a>

                                                <!-- Editar -->
                                                <a href="{{ route('calendario-editorial.edit', $item) }}"
                                                    class="group/btn inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-all duration-200 hover:shadow-sm hover:border-blue-300">
                                                    <svg class="w-4 h-4 group-hover/btn:text-blue-800 transition-colors"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    <span>Editar</span>
                                                </a>

                                                <!-- Publicar (solo admin_ti) -->
                                                @role('admin_ti')
                                                    @if ($item->estado !== 'publicado')
                                                        <form id="publicar-form-{{ $item->id }}"
                                                            action="{{ route('calendario-editorial.publicar', $item) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="button"
                                                                onclick="confirmarPublicacion({{ $item->id }})"
                                                                class="group/btn w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-green-600 rounded-lg transition-all duration-200 hover:shadow-sm">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                    </path>
                                                                </svg>
                                                                <span>Publicar</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <h3 class="text-xl font-bold text-gray-700 mb-2">No hay publicaciones
                                                    programadas</h3>
                                                <p class="text-gray-500 mb-6 max-w-md">
                                                    Comienza a planificar tu contenido para redes sociales creando una
                                                    nueva publicación
                                                </p>
                                                @role('admin_ti|calendario')
                                                    <a href="{{ route('calendario-editorial.create') }}"
                                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Crear primera publicación
                                                    </a>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <script>
                            let sortState = {
                                semana: null,
                                fecha: null,
                                contenido: null,
                                estado: null
                            };

                            function toggleSort(column) {
                                // Reset other columns
                                Object.keys(sortState).forEach(key => {
                                    if (key !== column) sortState[key] = null;
                                });

                                // Cycle through sort states: null -> asc -> desc -> null
                                if (sortState[column] === null) {
                                    sortState[column] = 'asc';
                                } else if (sortState[column] === 'asc') {
                                    sortState[column] = 'desc';
                                } else {
                                    sortState[column] = null;
                                }

                                sortTable(column, sortState[column]);
                            }

                            function sortTable(column, direction) {
                                const tbody = document.getElementById('tableBody');
                                const rows = Array.from(tbody.querySelectorAll('tr'));

                                if (!direction) {
                                    location.reload();
                                    return;
                                }

                                rows.sort((a, b) => {
                                    let aVal, bVal;

                                    switch(column) {
                                        case 'semana':
                                            aVal = parseInt(a.dataset.semana);
                                            bVal = parseInt(b.dataset.semana);
                                            break;
                                        case 'fecha':
                                            aVal = new Date(a.dataset.fecha);
                                            bVal = new Date(b.dataset.fecha);
                                            break;
                                        case 'contenido':
                                            aVal = a.dataset.contenido;
                                            bVal = b.dataset.contenido;
                                            break;
                                        case 'estado':
                                            aVal = a.dataset.estado;
                                            bVal = b.dataset.estado;
                                            break;
                                    }

                                    if (direction === 'asc') {
                                        return aVal > bVal ? 1 : -1;
                                    } else {
                                        return aVal < bVal ? 1 : -1;
                                    }
                                });

                                rows.forEach(row => tbody.appendChild(row));
                            }
                        </script>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View - Mejorado -->
            <div class="lg:hidden space-y-4">
                @forelse ($registros as $item)
                    @php
                        $estadoConfig = match ($item->estado) {
                            'publicado' => [
                                'bg' => 'bg-gradient-to-r from-green-50 to-emerald-50',
                                'border' => 'border-l-green-500',
                                'badge' =>
                                    'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                'dot' => 'bg-green-500',
                                'header' => 'from-green-500 to-emerald-600',
                            ],
                            'pendiente' => [
                                'bg' => 'bg-gradient-to-r from-orange-50 to-amber-50',
                                'border' => 'border-l-orange-500',
                                'badge' =>
                                    'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 border border-orange-200',
                                'dot' => 'bg-orange-500',
                                'header' => 'from-orange-500 to-amber-600',
                            ],
                            'reprogramado' => [
                                'bg' => 'bg-gradient-to-r from-yellow-50 to-amber-50',
                                'border' => 'border-l-yellow-500',
                                'badge' =>
                                    'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
                                'dot' => 'bg-yellow-500',
                                'header' => 'from-yellow-500 to-amber-600',
                            ],
                            'cancelado' => [
                                'bg' => 'bg-gradient-to-r from-red-50 to-rose-50',
                                'border' => 'border-l-red-500',
                                'badge' =>
                                    'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
                                'dot' => 'bg-red-500',
                                'header' => 'from-red-500 to-rose-600',
                            ],
                            default => [
                                'bg' => 'bg-gradient-to-r from-gray-50 to-gray-100',
                                'border' => 'border-l-gray-500',
                                'badge' =>
                                    'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-200',
                                'dot' => 'bg-gray-500',
                                'header' => 'from-gray-500 to-gray-600',
                            ],
                        };
                    @endphp

                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden {{ $estadoConfig['border'] }} border-l-4">
                        <!-- Card Header -->
                        <div class="p-5 {{ $estadoConfig['bg'] }}">
                            <div class="flex justify-between items-start mb-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-900">
                                            Semana {{ $item->semana }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $estadoConfig['badge'] }} capitalize">
                                            <span
                                                class="w-2 h-2 rounded-full {{ $estadoConfig['dot'] }} mr-1.5 animate-pulse"></span>
                                            {{ $item->estado }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $item->fecha_publicacion->format('d/m/Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $item->hora }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->dia }}
                                    </div>
                                </div>
                            </div>

                            <!-- Tema Principal -->
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ $item->tema }}
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ Str::limit($item->encabezado, 120) }}
                                </p>
                            </div>

                            <!-- Metadata Chips -->
                            <div class="space-y-3">
                                @if ($item->area)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-gray-500">Área:</span>
                                        <span
                                            class="px-3 py-1 bg-white/70 text-gray-700 rounded-full text-xs font-medium border border-gray-200">
                                            {{ $item->area }}
                                        </span>
                                    </div>
                                @endif

                                @if (!empty($item->contenido))
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-medium text-gray-500">Contenido:</span>
                                        @foreach ($item->contenido as $c)
                                            <span
                                                class="px-2.5 py-1 bg-white/70 text-gray-700 rounded-lg text-xs border border-gray-200">
                                                {{ $c }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if (!empty($item->publicar_en))
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-medium text-gray-500">Redes:</span>
                                        @foreach ($item->publicar_en as $red)
                                            <span
                                                class="px-2.5 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 rounded-lg text-xs font-medium border border-blue-100 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                                </svg>
                                                {{ $red }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer - Acciones -->
                        <div class="bg-white p-4 border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-2">
                                <!-- Ver -->
                                <a href="{{ route('calendario-editorial.show', $item) }}"
                                    class="flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Ver
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('calendario-editorial.edit', $item) }}"
                                    class="flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar
                                </a>

                                <!-- Enlace (si existe) -->
                                @if ($item->enlace)
                                    <div class="col-span-2 mt-2">
                                        <a href="{{ $item->enlace }}" target="_blank"
                                            class="flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                            Ver publicación
                                        </a>
                                    </div>
                                @endif

                                <!-- Publicar (solo admin_ti) -->
                                @role('admin_ti')
                                    @if ($item->estado !== 'publicado')
                                        <div class="col-span-2 mt-2">
                                            <form id="publicar-form-mobile-{{ $item->id }}"
                                                action="{{ route('calendario-editorial.publicar', $item) }}"
                                                method="POST">
                                                @csrf
                                                <button type="button"
                                                    onclick="confirmarPublicacion({{ $item->id }})"
                                                    class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm hover:shadow">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Publicar esta actividad
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endrole
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div
                                class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">Sin publicaciones programadas</h3>
                            <p class="text-gray-500 mb-6">Crea tu primera publicación para comenzar</p>
                            @role('admin_ti|calendario')
                                <a href="{{ route('calendario-editorial.create') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Nueva publicación
                                </a>
                            @endrole
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- SweetAlert2 Script -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function confirmarPublicacion(id) {
                    Swal.fire({
                        title: '¿Publicar esta actividad?',
                        text: 'Esta acción marcará la publicación como "publicado" y será visible.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, publicar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-5 py-2.5 rounded-lg font-medium',
                            cancelButton: 'px-5 py-2.5 rounded-lg font-medium'
                        },
                        buttonsStyling: false,
                        background: 'rgb(249 250 251)',
                        backdrop: 'rgba(0, 0, 0, 0.4)'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('publicar-form-' + id) ||
                                document.getElementById('publicar-form-mobile-' + id);
                            if (form) {
                                form.submit();
                            }
                        }
                    });
                }
            </script>
        </div>
    </div>
</x-app-layout>
