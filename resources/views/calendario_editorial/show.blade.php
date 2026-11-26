<x-app-layout>
    <div class="sm:py-2">
        <div class="max-w-5xl mx-auto">

            <div class="mb-8">
                <div class="flex items-center gap-2 mb-3">
                    <a href="{{ route('calendario-editorial.index') }}"
                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al calendario
                    </a>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            Detalle de Publicación
                        </h1>
                        <p class="text-gray-600 mt-1">Información completa de la publicación programada</p>
                    </div>

                    <div class="flex gap-3">
                        @if ($calendarioEditorial->puedeEditar(auth()->user()))
                            <a href="{{ route('calendario-editorial.edit', $calendarioEditorial) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-blue-300 text-blue-700 font-medium rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Editar publicación
                            </a>
                        @endif

                        @role('admin_ti')
                            @if ($calendarioEditorial->estado !== 'publicado')
                                <form method="POST"
                                    action="{{ route('calendario-editorial.publicar', $calendarioEditorial) }}"
                                    id="publicarForm">
                                    @csrf
                                    <button type="button" onclick="confirmarPublicacion()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Marcar como publicado
                                    </button>
                                </form>
                            @endif
                        @endrole
                    </div>
                </div>
            </div>

            <x-flash />

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                @php
                                    $estadoConfig = match ($calendarioEditorial->estado) {
                                        'publicado' => [
                                            'color' => 'text-green-800',
                                            'bg' => 'bg-green-100',
                                            'border' => 'border-green-300',
                                            'dot' => 'bg-green-500',
                                        ],
                                        'pendiente' => [
                                            'color' => 'text-orange-800',
                                            'bg' => 'bg-orange-100',
                                            'border' => 'border-orange-300',
                                            'dot' => 'bg-orange-500',
                                        ],
                                        'reprogramado' => [
                                            'color' => 'text-yellow-800',
                                            'bg' => 'bg-yellow-100',
                                            'border' => 'border-yellow-300',
                                            'dot' => 'bg-yellow-500',
                                        ],
                                        default => [
                                            'color' => 'text-red-800',
                                            'bg' => 'bg-red-100',
                                            'border' => 'border-red-300',
                                            'dot' => 'bg-red-500',
                                        ],
                                    };
                                @endphp

                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full {{ $estadoConfig['dot'] }}"></span>
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $estadoConfig['color'] }} {{ $estadoConfig['bg'] }} border {{ $estadoConfig['border'] }}">
                                        {{ strtoupper($calendarioEditorial->estado) }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">
                                ID: <span class="font-mono">{{ $calendarioEditorial->id }}</span>
                                • Última actualización: {{ $calendarioEditorial->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-lg border border-blue-200">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <div class="text-sm">
                                <div class="font-semibold text-blue-800">
                                    {{ $calendarioEditorial->fecha_publicacion->format('d/m/Y') }}
                                    @if ($calendarioEditorial->hora)
                                        • {{ $calendarioEditorial->hora }}
                                    @endif
                                </div>
                                <div class="text-blue-600">{{ $calendarioEditorial->dia }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">

                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Programación</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                    Semana del año
                                </div>
                                <div class="text-xl font-semibold text-gray-900">
                                    Semana {{ $calendarioEditorial->semana }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    Día de publicación
                                </div>
                                <div class="text-xl font-semibold text-gray-900">
                                    {{ $calendarioEditorial->dia }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Hora programada
                                </div>
                                <div class="text-xl font-semibold text-gray-900">
                                    @if ($calendarioEditorial->hora)
                                        {{ $calendarioEditorial->hora }}
                                    @else
                                        <span class="text-gray-400">No especificada</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Contenido</h2>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-3">
                                <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Tema / Título principal
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight">
                                        {{ $calendarioEditorial->tema }}
                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Encabezado / Descripción
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-gray-800 whitespace-pre-line leading-relaxed">
                                        {{ $calendarioEditorial->encabezado ?? 'No se ha proporcionado encabezado.' }}
                                    </p>
                                </div>
                            </div>

                            @if ($calendarioEditorial->area)
                                <div class="space-y-3">
                                    <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                        Área / Categoría
                                    </div>
                                    <div
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg border border-blue-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                        <span class="font-medium">{{ $calendarioEditorial->area }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-900">Tipo de contenido</h2>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    @forelse ($calendarioEditorial->contenido ?? [] as $c)
                                        <span
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-800 rounded-lg border border-gray-300 font-medium">
                                            @switch($c)
                                                @case('EN VIVO')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @break

                                                @case('IMAGEN')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @break

                                                @case('CARRUSEL')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                                        </path>
                                                    </svg>
                                                @break

                                                @case('HISTORIA')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6v6l4 2"></path>
                                                    </svg>
                                                @break

                                                @case('VIDEO')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @break
                                            @endswitch
                                            {{ $c }}
                                        </span>
                                        @empty
                                            <div class="text-gray-400 italic py-2">No se especificó tipo de contenido</div>
                                        @endforelse
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="p-2 bg-blue-50 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                </path>
                                            </svg>
                                        </div>
                                        <h2 class="text-lg font-semibold text-gray-900">Publicar en</h2>
                                    </div>

                                    <div class="flex flex-wrap gap-3">
                                        @forelse ($calendarioEditorial->publicar_en ?? [] as $r)
                                            <span
                                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-800 rounded-lg border border-blue-200 font-medium">
                                                @switch($r)
                                                    @case('FACEBOOK')
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                        </svg>
                                                    @break

                                                    @case('INSTAGRAM')
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                        </svg>
                                                    @break

                                                    @case('X')
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" />
                                                        </svg>
                                                    @break

                                                    @default
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.1021.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                            </path>
                                                        </svg>
                                                @endswitch
                                                {{ $r }}
                                            </span>
                                            @empty
                                                <div class="text-gray-400 italic py-2">No se especificaron redes sociales</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div>
                                        <div class="flex items-center gap-3 mb-6">
                                            <div class="p-2 bg-blue-50 rounded-lg">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h2 class="text-lg font-semibold text-gray-900">Enlace publicado</h2>
                                        </div>

                                        @if ($calendarioEditorial->enlace)
                                            <a href="{{ $calendarioEditorial->enlace }}" target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-3 bg-green-50 text-green-800 rounded-lg border border-green-200 hover:bg-green-100 hover:border-green-300 transition-all duration-200 group">
                                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                <div class="truncate max-w-xs sm:max-w-md">
                                                    {{ $calendarioEditorial->enlace }}
                                                </div>
                                            </a>
                                        @else
                                            <div
                                                class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-600 rounded-lg border border-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Aún no publicado</span>
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        // Orden en que se agregaron (el id crece con cada archivo subido)
                                        $adjuntos = $calendarioEditorial->adjuntos->sortBy('id')->values();
                                        $imagenes = $adjuntos->filter(fn ($a) => str_starts_with((string) $a->mime_type, 'image/'))->values();
                                        $otros = $adjuntos->reject(fn ($a) => str_starts_with((string) $a->mime_type, 'image/'))->values();
                                        // Registros muy viejos: solo tienen la columna adjunto_path, sin fila en adjuntos
                                        $soloPrincipal = $adjuntos->isEmpty() && $calendarioEditorial->adjunto_path;
                                        $galeria = $imagenes->map(fn ($a) => [
                                            'url' => route('calendario-editorial.adjuntos.show', $a),
                                            'nombre' => $a->nombre_original,
                                        ])->values();
                                    @endphp

                                    @if ($adjuntos->isNotEmpty() || $soloPrincipal)
                                        <div class="mt-8">
                                            <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900 mb-4">
                                                <span class="p-2 bg-blue-50 rounded-lg">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                </span>
                                                Archivos adjuntos
                                                <span class="text-sm font-normal text-gray-500">({{ $adjuntos->count() ?: 1 }})</span>
                                            </h2>

                                            {{-- Imágenes: miniaturas numeradas en el orden en que se agregaron --}}
                                            @if ($imagenes->isNotEmpty())
                                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                                                    @foreach ($imagenes as $adjunto)
                                                        <button type="button" onclick="abrirGaleria({{ $loop->index }})"
                                                            class="group relative aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-100 hover:ring-2 hover:ring-blue-400"
                                                            title="{{ $adjunto->nombre_original }}">
                                                            <img src="{{ $galeria[$loop->index]['url'] }}" alt="{{ $adjunto->nombre_original }}" loading="lazy"
                                                                class="h-full w-full object-cover"
                                                                onerror="this.replaceWith(Object.assign(document.createElement('div'), { className: 'flex h-full w-full items-center justify-center p-2 text-center text-xs text-red-600', textContent: '⚠️ No se pudo cargar la imagen' }))">
                                                            @if ($imagenes->count() > 1)
                                                                <span class="absolute left-1.5 top-1.5 rounded bg-black/60 px-1.5 py-0.5 text-[11px] font-semibold text-white">
                                                                    {{ $loop->iteration }}
                                                                </span>
                                                            @endif
                                                            <span class="absolute inset-x-0 bottom-0 truncate bg-black/50 px-2 py-1 text-left text-[11px] text-white">
                                                                {{ $adjunto->nombre_original }}
                                                            </span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Otros archivos (PDF, video, Office...) --}}
                                            <div class="space-y-2">
                                                @foreach ($otros as $adjunto)
                                                    @php
                                                        $url = route('calendario-editorial.adjuntos.show', $adjunto);
                                                        $mime = (string) $adjunto->mime_type;
                                                        $icono = str_contains($mime, 'video') ? '🎬' : (str_contains($mime, 'pdf') ? '📄' : '📎');
                                                    @endphp
                                                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                                                        <span class="text-xl">{{ $icono }}</span>
                                                        <button type="button" onclick="mostrarPreview(@js($url), @js($mime), @js($adjunto->nombre_original))"
                                                            class="min-w-0 flex-1 text-left hover:text-blue-700">
                                                            <span class="block truncate font-medium">{{ $adjunto->nombre_original }}</span>
                                                            <span class="block text-xs text-gray-500">
                                                                {{ strtoupper(pathinfo($adjunto->nombre_original, PATHINFO_EXTENSION)) }}
                                                                · {{ number_format(($adjunto->tamano ?? 0) / 1024, 1) }} KB
                                                            </span>
                                                        </button>
                                                        <a href="{{ $url }}" target="_blank" rel="noopener"
                                                            class="shrink-0 text-sm font-medium text-blue-600 hover:underline">Abrir</a>
                                                    </div>
                                                @endforeach

                                                @if ($soloPrincipal)
                                                    <a href="{{ route('calendario-editorial.adjunto-principal', $calendarioEditorial) }}" target="_blank" rel="noopener"
                                                        class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 hover:bg-gray-100">
                                                        <span class="text-xl">📎</span>
                                                        <span class="min-w-0 flex-1 truncate font-medium">{{ $calendarioEditorial->adjunto_nombre ?: 'Archivo adjunto' }}</span>
                                                        <span class="text-sm font-medium text-blue-600">Abrir</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Visor: galería de imágenes (‹ ›, teclado, deslizar) o un archivo suelto (PDF, video) --}}
                                    <div id="previewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-2 sm:p-4"
                                        role="dialog" aria-modal="true" aria-labelledby="previewTitulo">
                                        <div class="flex max-h-[95vh] w-full max-w-5xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">
                                            <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-4 py-3">
                                                <div class="min-w-0">
                                                    <h3 id="previewTitulo" class="truncate text-base font-semibold text-gray-900 sm:text-lg">Vista previa</h3>
                                                    <p id="previewContador" class="hidden text-xs font-medium text-gray-500"></p>
                                                </div>
                                                <div class="flex shrink-0 items-center gap-3">
                                                    <a id="previewAbrir" href="#" target="_blank" rel="noopener"
                                                        class="hidden text-sm font-medium text-blue-600 hover:underline sm:inline">Abrir en otra pestaña</a>
                                                    <button type="button" id="previewCerrar" onclick="cerrarPreview()" class="rounded text-gray-500 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" aria-label="Cerrar">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="previewEscenario" class="relative flex min-h-[40vh] flex-1 items-center justify-center overflow-auto bg-gray-50 p-3 sm:p-6">
                                                <div id="previewContent" class="flex w-full items-center justify-center"></div>

                                                <button type="button" id="previewAnterior" onclick="moverGaleria(-1)" aria-label="Imagen anterior" title="Anterior (←)"
                                                    class="hidden absolute left-2 top-1/2 h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow-lg ring-1 ring-gray-200 hover:bg-white disabled:cursor-not-allowed disabled:opacity-30 sm:left-4">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                </button>
                                                <button type="button" id="previewSiguiente" onclick="moverGaleria(1)" aria-label="Imagen siguiente" title="Siguiente (→)"
                                                    class="hidden absolute right-2 top-1/2 h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow-lg ring-1 ring-gray-200 hover:bg-white disabled:cursor-not-allowed disabled:opacity-30 sm:right-4">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>
                                            </div>

                                            {{-- Tira de miniaturas para saltar a cualquier imagen --}}
                                            <div id="previewTira" class="hidden gap-2 overflow-x-auto border-t border-gray-200 bg-white p-3"></div>
                                        </div>
                                    </div>

                                    <script>
                                        const GALERIA = @json($galeria);
                                        let indiceGaleria = -1; // -1: se está viendo un archivo suelto (PDF, video)

                                        const visor = {
                                            modal: document.getElementById('previewModal'),
                                            contenido: document.getElementById('previewContent'),
                                            titulo: document.getElementById('previewTitulo'),
                                            contador: document.getElementById('previewContador'),
                                            abrir: document.getElementById('previewAbrir'),
                                            anterior: document.getElementById('previewAnterior'),
                                            siguiente: document.getElementById('previewSiguiente'),
                                            tira: document.getElementById('previewTira'),
                                            escenario: document.getElementById('previewEscenario'),
                                        };

                                        function avisoArchivo() {
                                            return '<div class="text-center text-sm text-red-700 py-10">⚠️ No se pudo cargar el archivo.<br>' +
                                                '<span class="text-gray-500">Puede que no esté en el servidor o que la carpeta no tenga permiso de lectura.</span></div>';
                                        }

                                        function abrirModal(url, nombre) {
                                            visor.titulo.textContent = nombre || 'Vista previa';
                                            visor.abrir.href = url;
                                            visor.modal.classList.remove('hidden');
                                            document.body.style.overflow = 'hidden';
                                            document.getElementById('previewCerrar').focus({ preventScroll: true });
                                        }

                                        function navegacionVisible(visible) {
                                            const varias = visible && GALERIA.length > 1;
                                            visor.anterior.classList.toggle('hidden', !varias);
                                            visor.anterior.classList.toggle('flex', varias);
                                            visor.siguiente.classList.toggle('hidden', !varias);
                                            visor.siguiente.classList.toggle('flex', varias);
                                            visor.tira.classList.toggle('hidden', !varias);
                                            visor.tira.classList.toggle('flex', varias);
                                            visor.contador.classList.toggle('hidden', !varias);
                                            visor.contenido.classList.toggle('px-12', varias);
                                            visor.contenido.classList.toggle('sm:px-16', varias);
                                        }

                                        function armarTira() {
                                            if (visor.tira.childElementCount) return;
                                            GALERIA.forEach((item, i) => {
                                                const boton = document.createElement('button');
                                                boton.type = 'button';
                                                boton.title = `${i + 1}. ${item.nombre}`;
                                                boton.className = 'relative h-14 w-14 shrink-0 overflow-hidden rounded-md bg-gray-100 opacity-60 transition hover:opacity-100';
                                                boton.onclick = () => irAImagen(i);
                                                const img = document.createElement('img');
                                                img.src = item.url;
                                                img.alt = '';
                                                img.loading = 'lazy';
                                                img.className = 'h-full w-full object-cover';
                                                img.onerror = () => img.replaceWith(Object.assign(document.createElement('span'), { className: 'flex h-full w-full items-center justify-center text-lg', textContent: '⚠️' }));
                                                boton.appendChild(img);
                                                visor.tira.appendChild(boton);
                                            });
                                        }

                                        function mostrarImagenActual() {
                                            const item = GALERIA[indiceGaleria];
                                            visor.titulo.textContent = item.nombre;
                                            visor.abrir.href = item.url;
                                            visor.contador.textContent = `Imagen ${indiceGaleria + 1} de ${GALERIA.length}`;
                                            visor.anterior.disabled = indiceGaleria === 0;
                                            visor.siguiente.disabled = indiceGaleria === GALERIA.length - 1;

                                            const cargando = document.createElement('p');
                                            cargando.className = 'absolute text-sm text-gray-400';
                                            cargando.textContent = 'Cargando…';

                                            const img = document.createElement('img');
                                            img.alt = item.nombre;
                                            img.className = 'max-h-[62vh] w-auto max-w-full rounded-lg opacity-0 transition-opacity duration-200';
                                            img.onload = () => { img.classList.remove('opacity-0'); cargando.remove(); };
                                            img.onerror = () => { visor.contenido.innerHTML = avisoArchivo(); };

                                            visor.contenido.replaceChildren(cargando, img);
                                            img.src = item.url;

                                            [...visor.tira.children].forEach((boton, i) => {
                                                const activa = i === indiceGaleria;
                                                boton.classList.toggle('opacity-60', !activa);
                                                boton.classList.toggle('ring-2', activa);
                                                boton.classList.toggle('ring-blue-500', activa);
                                                if (activa) boton.scrollIntoView({ block: 'nearest', inline: 'center' });
                                            });

                                            // Precarga la anterior y la siguiente para que el cambio sea inmediato
                                            [indiceGaleria - 1, indiceGaleria + 1]
                                                .filter((i) => i >= 0 && i < GALERIA.length)
                                                .forEach((i) => { new Image().src = GALERIA[i].url; });
                                        }

                                        function abrirGaleria(indice) {
                                            indiceGaleria = indice;
                                            armarTira();
                                            navegacionVisible(true);
                                            abrirModal(GALERIA[indice].url, GALERIA[indice].nombre);
                                            mostrarImagenActual();
                                        }

                                        function irAImagen(indice) {
                                            if (indiceGaleria < 0 || indice < 0 || indice >= GALERIA.length || indice === indiceGaleria) return;
                                            indiceGaleria = indice;
                                            mostrarImagenActual();
                                        }

                                        function moverGaleria(paso) {
                                            irAImagen(indiceGaleria + paso);
                                        }

                                        // Archivo suelto (PDF, video u otro): sin navegación
                                        function mostrarPreview(url, mimeType, nombre) {
                                            mimeType = mimeType || '';
                                            indiceGaleria = -1;
                                            navegacionVisible(false);
                                            visor.contenido.innerHTML = '';

                                            if (mimeType.includes('video')) {
                                                const video = document.createElement('video');
                                                video.controls = true;
                                                video.className = 'max-h-[70vh] w-full rounded-lg';
                                                video.src = url;
                                                video.onerror = () => { visor.contenido.innerHTML = avisoArchivo(); };
                                                visor.contenido.appendChild(video);
                                            } else if (mimeType.includes('pdf')) {
                                                const iframe = document.createElement('iframe');
                                                iframe.src = url;
                                                iframe.className = 'h-[70vh] w-full rounded-lg';
                                                visor.contenido.appendChild(iframe);
                                            } else {
                                                visor.contenido.innerHTML = '<div class="text-center text-gray-500 py-6"><p>No hay vista previa para este tipo de archivo.</p></div>';
                                            }

                                            abrirModal(url, nombre);
                                        }

                                        function cerrarPreview() {
                                            visor.modal.classList.add('hidden');
                                            visor.contenido.innerHTML = '';
                                            document.body.style.overflow = '';
                                            indiceGaleria = -1;
                                        }

                                        visor.modal.addEventListener('click', (e) => {
                                            if (e.target === visor.modal) cerrarPreview();
                                        });

                                        document.addEventListener('keydown', (e) => {
                                            if (visor.modal.classList.contains('hidden')) return;
                                            if (e.key === 'Escape') cerrarPreview();
                                            if (e.key === 'ArrowRight') { e.preventDefault(); moverGaleria(1); }
                                            if (e.key === 'ArrowLeft') { e.preventDefault(); moverGaleria(-1); }
                                        });

                                        // Deslizar con el dedo en el teléfono
                                        let toqueInicioX = null;
                                        visor.escenario.addEventListener('touchstart', (e) => { toqueInicioX = e.changedTouches[0].clientX; }, { passive: true });
                                        visor.escenario.addEventListener('touchend', (e) => {
                                            if (toqueInicioX === null) return;
                                            const distancia = e.changedTouches[0].clientX - toqueInicioX;
                                            toqueInicioX = null;
                                            if (Math.abs(distancia) > 50) moverGaleria(distancia < 0 ? 1 : -1);
                                        });
                                    </script>

                                </div>

                                @if ($calendarioEditorial->etiquetas || $calendarioEditorial->comentario)
                                    <div class="mt-8 pt-8 border-t border-gray-200">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                            @if ($calendarioEditorial->etiquetas)
                                                <div class="space-y-3">
                                                    <div class="text-sm font-medium text-gray-500">Etiquetas (páginas a
                                                        etiquetar)</div>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach (explode(',', $calendarioEditorial->etiquetas) as $etiqueta)
                                                            <span
                                                                class="px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg border border-purple-200 text-sm">
                                                                {{ trim($etiqueta) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($calendarioEditorial->comentario)
                                                <div class="space-y-3">
                                                    <div class="text-sm font-medium text-gray-500">Comentarios adicionales
                                                    </div>
                                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                                        <p class="text-gray-700 whitespace-pre-line">
                                                            {{ $calendarioEditorial->comentario }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if ($calendarioEditorial->nota_admin)
                                    @role('admin_ti|gerencia')
                                        <div class="mt-8 pt-8 border-t border-gray-200 space-y-3">
                                            <div class="text-sm font-medium text-gray-500">Nota administrativa (solo TI y gerencia)</div>
                                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                                                <p class="text-gray-700 whitespace-pre-line">{{ $calendarioEditorial->nota_admin }}</p>
                                            </div>
                                        </div>
                                    @endrole
                                @endif
                            </div>

                            <div class="p-8 bg-gray-50">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2 bg-gray-100 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-900">Auditoría y seguimiento</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <div class="text-sm font-medium text-gray-500">Creado por</div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    {{ $calendarioEditorial->creador?->name ?? 'No especificado' }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $calendarioEditorial->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="text-sm font-medium text-gray-500">Publicado por</div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    {{ $calendarioEditorial->publicadoPor?->name ?? 'No publicado' }}</div>
                                                @if ($calendarioEditorial->publicadoPor)
                                                    <div class="text-sm text-gray-500">Estado: Publicado</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="text-sm font-medium text-gray-500">Última modificación</div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    {{ $calendarioEditorial->updated_at->format('d/m/Y H:i') }}</div>
                                                <div class="text-sm text-gray-500">Actualizado</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium">Información de la publicación</p>
                                <p class="mt-1">Esta vista muestra todos los detalles de la publicación. Puede editar la
                                    información haciendo clic en el botón "Editar publicación" en la parte superior.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function confirmarPublicacion() {
                    Swal.fire({
                        title: '¿Marcar como publicado?',
                        text: 'Esta acción cambiará el estado de la publicación a "publicado" y será visible en el calendario general.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, marcar como publicado',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'px-4 py-2 rounded-lg font-medium',
                            cancelButton: 'px-4 py-2 rounded-lg font-medium'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('publicarForm').submit();
                        }
                    });
                }
            </script>
        </x-app-layout>
