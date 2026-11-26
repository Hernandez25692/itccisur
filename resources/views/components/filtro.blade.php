{{-- Un campo dentro de <x-filtros>: etiqueta arriba y el control (input/select con clase filtro-control). --}}
@props(['label', 'ancho' => 1])

@php
    $spans = [2 => 'sm:col-span-2', 3 => 'sm:col-span-2 lg:col-span-3', 4 => 'sm:col-span-2 lg:col-span-4'];
@endphp

<label {{ $attributes->merge(['class' => 'block min-w-0 ' . ($spans[$ancho] ?? '')]) }}>
    <span class="filtro-label">{{ $label }}</span>
    {{ $slot }}
</label>
