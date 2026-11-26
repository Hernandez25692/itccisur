{{--
    Barra de filtros común: grid de campos <x-filtro>, botones Filtrar/Limpiar y,
    en pantallas chicas, se pliega para no empujar el contenido hacia abajo.
    Props: limpiar (URL), activos (cuántos filtros aplicados), columnas (3-6), resumen (texto a la derecha).
--}}
@props(['limpiar' => null, 'activos' => 0, 'columnas' => 4, 'resumen' => null, 'accion' => null])

@php
    // Con el menú lateral abierto, en "lg" el contenido mide ~650px: ahí no caben más de 3 columnas
    $grid = [
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        4 => 'lg:grid-cols-3 xl:grid-cols-4',
        5 => 'lg:grid-cols-3 xl:grid-cols-5',
        6 => 'lg:grid-cols-3 xl:grid-cols-6',
    ][$columnas] ?? 'lg:grid-cols-3 xl:grid-cols-4';
@endphp

<form method="GET" action="{{ $accion ?? url()->current() }}"
    x-data="{ abierto: {{ $activos ? 'true' : 'false' }} || window.matchMedia('(min-width: 1024px)').matches }"
    {{ $attributes->merge(['class' => 'filtros']) }}>

    <div class="flex flex-wrap items-center justify-between gap-2">
        <button type="button" class="filtros-toggle" @click="abierto = !abierto" :aria-expanded="abierto.toString()">
            <svg class="h-4 w-4 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span>Filtros</span>
            @if ($activos)
                <span class="filtros-contador" title="Filtros aplicados">{{ $activos }}</span>
            @endif
            <svg class="h-4 w-4 text-gray-400 transition-transform" :class="abierto && 'rotate-180'" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        @if ($resumen)
            <span class="text-xs text-gray-500">{{ $resumen }}</span>
        @endif
    </div>

    <div x-show="abierto" x-cloak class="mt-4">
        <div class="grid grid-flow-row-dense grid-cols-1 gap-3 sm:grid-cols-2 {{ $grid }}">
            {{ $slot }}
        </div>

        <div class="filtros-acciones">
            @if ($activos && $limpiar)
                <a href="{{ $limpiar }}" class="btn-limpiar">Limpiar</a>
            @endif
            <button type="submit" class="btn-filtrar">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filtrar
            </button>
        </div>
    </div>
</form>
