<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($i)
        @case('buscar')
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
        @break

        @case('anterior')
            <path d="m15 18-6-6 6-6" />
        @break

        @case('siguiente')
            <path d="m9 18 6-6-6-6" />
        @break

        @case('bandera')
            <path d="M5 21V4" />
            <path d="M5 4h11l-2 4 2 4H5" />
        @break

        @case('reloj-arena')
            <path d="M5 22h14M5 2h14" />
            <path d="M17 22v-4.2a2 2 0 0 0-.6-1.4L12 12l-4.4 4.4a2 2 0 0 0-.6 1.4V22" />
            <path d="M7 2v4.2a2 2 0 0 0 .6 1.4L12 12l4.4-4.4a2 2 0 0 0 .6-1.4V2" />
        @break

        @case('menos')
            <circle cx="12" cy="12" r="9" />
            <path d="M8 12h8" />
        @break

        @case('ok')
            <circle cx="12" cy="12" r="9" />
            <path d="m8.5 12.5 2.5 2.5 4.5-5" />
        @break

        @case('alerta')
            <path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z" />
            <path d="M12 9v4M12 17h.01" />
        @break

        @case('mas')
            <path d="M12 5v14M5 12h14" />
        @break

        @case('actualizar')
            <path d="M21 12a9 9 0 1 1-2.6-6.4" />
            <path d="M21 4v5h-5" />
        @break

        @case('calendario')
            <rect x="3" y="5" width="18" height="16" rx="2" />
            <path d="M16 3v4M8 3v4M3 10h18" />
        @break
    @endswitch
</svg>
