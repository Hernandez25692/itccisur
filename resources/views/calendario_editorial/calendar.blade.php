<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --azul-profundo: #0B1F3A;
            --azul-medio: #112849;
            --azul-panel: #0D2040;
            --azul-borde: #1A3358;
            --azul-hover: #1C3D6A;
            --dorado: #C5A049;
            --dorado-claro: #D4B46A;
            --dorado-suave: rgba(197,160,73,.15);
            --dorado-borde: rgba(197,160,73,.35);
            --blanco: #F0EDE6;
            --gris-texto: #8FA5C0;
            --radius: 14px;
        }

        .ccisur-page {
            min-height: 100vh;
            background: var(--azul-profundo);
            background-image:
                radial-gradient(ellipse 80% 50% at 10% 0%, rgba(197,160,73,.08) 0%, transparent 55%),
                radial-gradient(ellipse 60% 40% at 90% 100%, rgba(197,160,73,.06) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C5A049' fill-opacity='0.025'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            padding: 2.5rem 1.5rem;
            font-family: 'DM Sans', sans-serif;
        }

        .ccisur-wrapper {
            max-width: 1280px;
            margin: 0 auto;
        }

        .ccisur-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--azul-borde);
            position: relative;
        }

        .ccisur-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 120px;
            height: 2px;
            background: linear-gradient(90deg, var(--dorado), transparent);
        }

        .ccisur-header-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--blanco);
            line-height: 1.1;
            margin: 0;
            letter-spacing: -.5px;
        }

        .ccisur-header-left h1 span {
            color: var(--dorado);
        }

        .ccisur-header-left p {
            margin: .35rem 0 0;
            font-size: .85rem;
            color: var(--gris-texto);
            font-weight: 400;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .ccisur-badge {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--dorado-suave);
            border: 1px solid var(--dorado-borde);
            border-radius: 50px;
            padding: .45rem 1rem;
            font-size: .78rem;
            font-weight: 600;
            color: var(--dorado-claro);
            letter-spacing: .3px;
        }

        .ccisur-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--dorado);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1); }
            50% { opacity:.5; transform:scale(.7); }
        }

        .ccisur-card {
            background: var(--azul-panel);
            border: 1px solid var(--azul-borde);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow:
                0 0 0 1px rgba(197,160,73,.05),
                0 20px 60px rgba(0,0,0,.4),
                inset 0 1px 0 rgba(197,160,73,.07);
            position: relative;
            overflow: hidden;
        }

        .ccisur-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--dorado) 0%, var(--dorado-claro) 50%, transparent 100%);
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .fc .fc-toolbar {
            margin-bottom: 1.5rem !important;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .fc .fc-toolbar-title {
            font-family: 'Playfair Display', serif !important;
            font-size: 1.6rem !important;
            font-weight: 700 !important;
            color: var(--blanco) !important;
            letter-spacing: -.3px;
        }

        .fc .fc-button {
            background: var(--azul-medio) !important;
            border: 1px solid var(--azul-borde) !important;
            color: var(--blanco) !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: .78rem !important;
            font-weight: 600 !important;
            letter-spacing: .5px !important;
            text-transform: uppercase !important;
            padding: .45rem .9rem !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            transition: all .2s ease !important;
        }

        .fc .fc-button:hover {
            background: var(--azul-hover) !important;
            border-color: var(--dorado-borde) !important;
            color: var(--dorado-claro) !important;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: var(--dorado) !important;
            border-color: var(--dorado) !important;
            color: var(--azul-profundo) !important;
        }

        .fc .fc-today-button {
            background: var(--dorado-suave) !important;
            border-color: var(--dorado-borde) !important;
            color: var(--dorado-claro) !important;
        }

        .fc .fc-col-header {
            background: var(--azul-profundo) !important;
        }

        .fc .fc-col-header-cell {
            padding: .75rem 0 !important;
            border-color: var(--azul-borde) !important;
        }

        .fc .fc-col-header-cell-cushion {
            font-family: 'DM Sans', sans-serif !important;
            font-size: .7rem !important;
            font-weight: 600 !important;
            letter-spacing: 1.2px !important;
            text-transform: uppercase !important;
            color: var(--gris-texto) !important;
            text-decoration: none !important;
        }

        .fc .fc-daygrid-day {
            background: var(--azul-profundo) !important;
            border-color: var(--azul-borde) !important;
            transition: background .15s ease;
        }

        .fc .fc-daygrid-day:hover {
            background: rgba(197,160,73,.04) !important;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(197,160,73,.08) !important;
        }

        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            background: var(--dorado) !important;
            color: var(--azul-profundo) !important;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
            font-weight: 700;
        }

        .fc .fc-daygrid-day-number {
            font-family: 'DM Sans', sans-serif !important;
            font-size: .8rem !important;
            font-weight: 500 !important;
            color: var(--gris-texto) !important;
            text-decoration: none !important;
            padding: 6px 8px !important;
        }

        .fc-theme-standard td,
        .fc-theme-standard th,
        .fc-theme-standard .fc-scrollgrid {
            border-color: var(--azul-borde) !important;
        }

        .fc-theme-standard .fc-scrollgrid {
            border-radius: 10px;
            overflow: hidden;
        }

        .fc .fc-daygrid-event {
            background: transparent !important;
            border: none !important;
            margin: 1px 4px !important;
        }

        .fc .fc-daygrid-event-harness {
            margin-bottom: 2px;
        }

        .event-pill {
            display: flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, rgba(197,160,73,.2) 0%, rgba(197,160,73,.1) 100%);
            border: 1px solid var(--dorado-borde);
            color: var(--dorado-claro);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all .2s ease;
            cursor: pointer;
            letter-spacing: .2px;
        }

        .event-pill::before {
            content: '';
            flex-shrink: 0;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--dorado);
        }

        .event-pill:hover {
            background: var(--dorado) !important;
            border-color: var(--dorado) !important;
            color: var(--azul-profundo) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(197,160,73,.3);
        }

        .ccisur-legend {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--azul-borde);
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: .45rem;
            font-size: .75rem;
            font-weight: 500;
            color: var(--gris-texto);
            letter-spacing: .3px;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 2px;
        }

        .legend-dot.gold  { background: var(--dorado); }
        .legend-dot.today { background: var(--dorado); border-radius: 50%; }

        /* Vista de lista (teléfonos) con el mismo tema oscuro */
        .fc .fc-list {
            border-color: var(--azul-borde) !important;
            border-radius: 10px;
            overflow: hidden;
        }

        .fc .fc-list-day-cushion,
        .fc .fc-list-empty {
            background: var(--azul-medio) !important;
            color: var(--dorado-claro) !important;
        }

        .fc .fc-list-event td {
            background: var(--azul-panel) !important;
            border-color: var(--azul-borde) !important;
            color: var(--blanco);
        }

        .fc .fc-list-event:hover td {
            background: var(--azul-hover) !important;
        }

        .fc .fc-list-event-time {
            color: var(--gris-texto);
        }

        .fc .fc-list-event-title .event-pill {
            white-space: normal;
        }

        @media (max-width: 640px) {
            .ccisur-header {
                flex-direction: column;
                align-items: flex-start;
                gap: .75rem;
            }

            .ccisur-page {
                padding: 1.25rem .75rem;
            }

            .ccisur-card {
                padding: 1rem;
            }

            .fc .fc-toolbar {
                flex-wrap: wrap;
                gap: .5rem;
            }

            .fc .fc-toolbar {
                justify-content: center;
            }

            .fc .fc-toolbar-title {
                font-size: 1.2rem !important;
            }
        }
        .event-pill.estado {
            background: color-mix(in srgb, var(--color-estado) 18%, transparent);
            border-color: color-mix(in srgb, var(--color-estado) 55%, transparent);
            color: var(--blanco);
        }

        .event-pill.estado::before {
            background: var(--color-estado);
        }

        .event-pill .hora {
            color: var(--gris-texto);
            font-weight: 500;
        }

        .ccisur-filtros {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .ccisur-filtros select,
        .ccisur-filtros input {
            width: 100%;
            min-width: 0;
            height: 2.5rem;
            background-color: var(--azul-medio);
            border: 1px solid var(--azul-borde);
            color: var(--blanco);
            border-radius: 8px;
            padding: .5rem .75rem;
            font-size: .85rem;
        }

        .ccisur-filtros select {
            padding-right: 2.25rem;
            text-overflow: ellipsis;
        }

        .ccisur-filtros .acciones {
            grid-column: 1 / -1;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .5rem;
        }

        .ccisur-filtros a,
        .ccisur-filtros button {
            border-radius: 8px;
            padding: .5rem 1rem;
            font-size: .85rem;
            font-weight: 600;
            border: 1px solid var(--dorado-borde);
            color: var(--dorado-claro);
            background: transparent;
            text-decoration: none;
        }

        .ccisur-filtros button {
            background: var(--dorado);
            color: var(--azul-profundo);
        }

        .ccisur-ayuda {
            color: var(--gris-texto);
            font-size: .75rem;
            margin-top: .75rem;
        }
    </style>

    <div class="ccisur-page">
        <div class="ccisur-wrapper">
            <div class="ccisur-header">
                <div class="ccisur-header-left">
                    <h1>Calendario <span>Editorial</span></h1>
                    <p>Cámara de Comercio e Industria del Sur · CCISUR</p>
                </div>
                <div class="ccisur-badge">
                    <span class="ccisur-badge-dot"></span>
                    Publicaciones programadas
                </div>
            </div>

            <div class="ccisur-card">
                <form id="filtrosCalendario" class="ccisur-filtros" method="GET">
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar tema o etiqueta">
                    <select name="estado">
                        <option value="">Todos los estados</option>
                        @foreach (\App\Models\CalendarioEditorial::ESTADOS as $estado)
                            <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                    <select name="red">
                        <option value="">Todas las redes</option>
                        @foreach (\App\Models\CalendarioEditorial::REDES as $red)
                            <option value="{{ $red }}" @selected(request('red') === $red)>{{ $red }}</option>
                        @endforeach
                    </select>
                    <select name="area">
                        <option value="">Todas las áreas</option>
                        @foreach (\App\Models\CalendarioEditorial::AREAS as $area)
                            <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                        @endforeach
                    </select>
                    <select name="tipo">
                        <option value="">Todo tipo de contenido</option>
                        @foreach (\App\Models\CalendarioEditorial::TIPOS_CONTENIDO as $tipo)
                            <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    <div class="acciones">
                    <button type="submit">Filtrar</button>
                    <a href="{{ route('calendario-editorial.calendar') }}">Limpiar</a>
                    <a href="{{ route('calendario-editorial.index', request()->only(['estado', 'area', 'red', 'tipo', 'buscar'])) }}">Ver como lista</a>
                    </div>
                </form>

                <div id="calendar"></div>

                <div class="ccisur-legend">
                    @foreach (\App\Models\CalendarioEditorial::COLORES as $estado => $color)
                        <div class="legend-item">
                            <span class="legend-dot" style="background: {{ $color }}"></span>
                            {{ ucfirst($estado) }}
                        </div>
                    @endforeach
                    <div class="legend-item">
                        <span class="legend-dot today"></span>
                        Día actual
                    </div>
                </div>

                @role('admin_ti|calendario')
                    <p class="ccisur-ayuda">Haz clic en un día para programar una publicación en esa fecha.</p>
                @endrole
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const filtros = document.getElementById('filtrosCalendario');
            const puedeCrear = @json(auth()->user()->hasAnyRole(['admin_ti', 'calendario']));
            const urlCrear = @json(route('calendario-editorial.create'));

            // El título viene de lo que escribe el usuario: se escapa antes de armar el HTML
            const escapar = (texto) => String(texto ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const calendar = new FullCalendar.Calendar(calendarEl, {
                // En el teléfono la cuadrícula del mes no se lee: se abre en lista
                initialView: window.matchMedia('(max-width: 640px)').matches ? 'listMonth' : 'dayGridMonth',
                locale: 'es',
                height: 'auto',
                expandRows: true,
                dayMaxEvents: 3,
                displayEventTime: false,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },

                // Solo se pide el rango visible, con los filtros del formulario
                events: {
                    url: @json(route('calendario-editorial.calendar.eventos')),
                    extraParams: () => Object.fromEntries(new FormData(filtros)),
                    failure: () => alert('No se pudieron cargar las publicaciones.'),
                },

                eventContent: function (info) {
                    const props = info.event.extendedProps;
                    const hora = props.hora ? `<span class="hora">${escapar(props.hora)}</span> ` : '';
                    const detalle = [props.estado, props.area, (props.redes || []).join(', ')].filter(Boolean).join(' · ');

                    return {
                        html: `<a href="${escapar(info.event.url)}" class="event-pill estado"
                                  style="--color-estado: ${escapar(info.event.backgroundColor)}"
                                  title="${escapar(info.event.title)}${detalle ? ' — ' + escapar(detalle) : ''}">${hora}${escapar(info.event.title)}</a>`
                    };
                },

                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },

                dateClick: function (info) {
                    if (puedeCrear) {
                        window.location.href = `${urlCrear}?fecha=${info.dateStr.substring(0, 10)}`;
                    }
                }
            });

            calendar.render();

            filtros.addEventListener('submit', function (e) {
                e.preventDefault();
                const params = new URLSearchParams(new FormData(filtros));
                history.replaceState(null, '', `${location.pathname}?${params}`);
                calendar.refetchEvents();
            });
        });
    </script>
</x-app-layout>
