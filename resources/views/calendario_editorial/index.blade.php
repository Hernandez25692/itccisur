<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Calendario Editorial – Redes Sociales
                        </h1>
                        <p class="text-gray-600 mt-1">Gestión de publicaciones programadas para redes sociales
                            institucionales</p>
                    </div>

                    @role('admin_ti|calendario')
                        <a href="{{ route('calendario-editorial.create') }}"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Nueva Publicación
                        </a>
                    @endrole
                </div>

                <!-- Filters and Stats (optional future enhancement) -->
                <div class="mt-6 flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-sm text-gray-600">Publicado</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                        <span class="text-sm text-gray-600">Pendiente</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                        <span class="text-sm text-gray-600">Reprogramado</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-sm text-gray-600">Cancelado</span>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-24">
                                    Semana
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-40">
                                    Fecha y Hora
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Contenido
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
                                    Estado
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-40">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($registros as $item)
                                @php
                                    $estadoConfig = match ($item->estado) {
                                        'publicado' => [
                                            'bg' => 'bg-green-50',
                                            'text' => 'text-green-800',
                                            'badge' => 'bg-green-100 text-green-800 border border-green-200',
                                            'dot' => 'bg-green-500',
                                        ],
                                        'pendiente' => [
                                            'bg' => 'bg-orange-50',
                                            'text' => 'text-orange-800',
                                            'badge' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                            'dot' => 'bg-orange-500',
                                        ],
                                        'reprogramado' => [
                                            'bg' => 'bg-yellow-50',
                                            'text' => 'text-yellow-800',
                                            'badge' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'dot' => 'bg-yellow-500',
                                        ],
                                        'cancelado' => [
                                            'bg' => 'bg-red-50',
                                            'text' => 'text-red-800',
                                            'badge' => 'bg-red-100 text-red-800 border border-red-200',
                                            'dot' => 'bg-red-500',
                                        ],
                                        default => [
                                            'bg' => 'bg-gray-50',
                                            'text' => 'text-gray-800',
                                            'badge' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                            'dot' => 'bg-gray-500',
                                        ],
                                    };
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors duration-150 border-l-4 {{ $estadoConfig['bg'] }}"
                                    style="border-left-color: {{ $estadoConfig['dot'] == 'bg-green-500' ? '#10B981' : ($estadoConfig['dot'] == 'bg-orange-500' ? '#F97316' : ($estadoConfig['dot'] == 'bg-yellow-500' ? '#EAB308' : '#EF4444')) }}">

                                    <!-- Semana -->
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $item->semana }}
                                        </div>
                                    </td>

                                    <!-- Fecha y Hora -->
                                    <td class="px-6 py-5">
                                        <div class="space-y-1">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->fecha_publicacion->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $item->dia }}
                                            </div>
                                            <div class="text-sm font-semibold text-gray-700">
                                                {{ $item->hora }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contenido Principal -->
                                    <td class="px-6 py-5">
                                        <div class="space-y-3">
                                            <!-- Tema -->
                                            <div>
                                                <h3 class="text-base font-semibold text-gray-900 mb-1">
                                                    {{ $item->tema }}
                                                </h3>
                                                <p class="text-sm text-gray-600 line-clamp-2">
                                                    {{ $item->encabezado }}
                                                </p>
                                            </div>

                                            <!-- Metadata -->
                                            <div class="flex flex-wrap gap-3 text-sm">
                                                <!-- Área -->
                                                @if ($item->area)
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                            </path>
                                                        </svg>
                                                        <span class="text-gray-700">{{ $item->area }}</span>
                                                    </div>
                                                @endif

                                                <!-- Tipo de Contenido -->
                                                @if (!empty($item->contenido))
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <div class="flex gap-1">
                                                            @foreach ($item->contenido as $c)
                                                                <span
                                                                    class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs border border-gray-200">
                                                                    {{ $c }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Redes Sociales -->
                                                @if (!empty($item->publicar_en))
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                            </path>
                                                        </svg>
                                                        <div class="flex gap-1">
                                                            @foreach ($item->publicar_en as $red)
                                                                <span
                                                                    class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100">
                                                                    {{ $red }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Enlace -->
                                                @if ($item->enlace)
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                            </path>
                                                        </svg>
                                                        <a href="{{ $item->enlace }}" target="_blank"
                                                            class="text-blue-600 hover:text-blue-800 hover:underline text-xs">
                                                            Ver publicación
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $estadoConfig['dot'] }}"></span>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $estadoConfig['badge'] }} capitalize">
                                                {{ $item->estado }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <!-- Ver -->
                                            <a href="{{ route('calendario-editorial.show', $item) }}"
                                                class="inline-flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Ver
                                            </a>

                                            <!-- Editar -->
                                            <a href="{{ route('calendario-editorial.edit', $item) }}"
                                                class="inline-flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Editar
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
                                                            class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            Publicar
                                                        </button>
                                                    </form>
                                                @endif
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500 mb-2">No hay publicaciones
                                                programadas</p>
                                            <p class="text-sm text-gray-400">Comienza creando una nueva publicación</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-4">
                @forelse ($registros as $item)
                    @php
                        $estadoConfig = match ($item->estado) {
                            'publicado' => [
                                'bg' => 'bg-green-50',
                                'border' => 'border-l-green-500',
                                'badge' => 'bg-green-100 text-green-800 border border-green-200',
                                'dot' => 'bg-green-500',
                            ],
                            'pendiente' => [
                                'bg' => 'bg-orange-50',
                                'border' => 'border-l-orange-500',
                                'badge' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                'dot' => 'bg-orange-500',
                            ],
                            'reprogramado' => [
                                'bg' => 'bg-yellow-50',
                                'border' => 'border-l-yellow-500',
                                'badge' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                'dot' => 'bg-yellow-500',
                            ],
                            'cancelado' => [
                                'bg' => 'bg-red-50',
                                'border' => 'border-l-red-500',
                                'badge' => 'bg-red-100 text-red-800 border border-red-200',
                                'dot' => 'bg-red-500',
                            ],
                            default => [
                                'bg' => 'bg-gray-50',
                                'border' => 'border-l-gray-500',
                                'badge' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                'dot' => 'bg-gray-500',
                            ],
                        };
                    @endphp

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden {{ $estadoConfig['border'] }} border-l-4">
                        <div class="p-5">
                            <!-- Header: Semana, Fecha y Estado -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-900">
                                            Semana {{ $item->semana }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoConfig['badge'] }} capitalize">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full {{ $estadoConfig['dot'] }} mr-1"></span>
                                            {{ $item->estado }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $item->fecha_publicacion->format('d/m/Y') }} • {{ $item->dia }}
                                    </div>
                                    <div class="text-base font-semibold text-gray-900">
                                        {{ $item->hora }}
                                    </div>
                                </div>
                            </div>

                            <!-- Tema Principal -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    {{ $item->tema }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-3">
                                    {{ $item->encabezado }}
                                </p>
                            </div>

                            <!-- Metadata Chips -->
                            <div class="space-y-3 mb-5">
                                <!-- Área -->
                                @if ($item->area)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-gray-500">Área:</span>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                            {{ $item->area }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Tipo de Contenido -->
                                @if (!empty($item->contenido))
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-xs font-medium text-gray-500">Contenido:</span>
                                        @foreach ($item->contenido as $c)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                                {{ $c }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Redes Sociales -->
                                @if (!empty($item->publicar_en))
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-xs font-medium text-gray-500">Redes:</span>
                                        @foreach ($item->publicar_en as $red)
                                            <span
                                                class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100">
                                                {{ $red }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Enlace -->
                                @if ($item->enlace)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $item->enlace }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                            Ver publicación
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-100">
                                <!-- Ver -->
                                <a href="{{ route('calendario-editorial.show', $item) }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
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
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar
                                </a>

                                <!-- Publicar (solo admin_ti) -->
                                @role('admin_ti')
                                    @if ($item->estado !== 'publicado')
                                        <form id="publicar-form-mobile-{{ $item->id }}"
                                            action="{{ route('calendario-editorial.publicar', $item) }}" method="POST"
                                            class="flex-1">
                                            @csrf
                                            <button type="button" onclick="confirmarPublicacion({{ $item->id }})"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Publicar
                                            </button>
                                        </form>
                                    @endif
                                @endrole
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 mb-2">No hay publicaciones programadas</p>
                            <p class="text-sm text-gray-400">Comienza creando una nueva publicación</p>
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
                            popup: 'rounded-xl',
                            confirmButton: 'px-4 py-2 rounded-lg',
                            cancelButton: 'px-4 py-2 rounded-lg'
                        }
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
