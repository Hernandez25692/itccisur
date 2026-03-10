<x-app-layout>
    @php
        $calendarEvents = $eventos
            ->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'title' => $evento->tema,
                    'start' => optional($evento->fecha_publicacion)->format('Y-m-d'),
                    'url' => route('calendario-editorial.show', $evento),
                    'estado' => $evento->estado,
                ];
            })
            ->values();
    @endphp

    <style>
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #e5e7eb;
        }

        .fc .fc-scrollgrid {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .fc-col-header-cell {
            background: #f8fafc;
        }

        .fc-col-header-cell-cushion {
            color: #334155;
            font-weight: 700;
            padding: 12px 8px;
            text-transform: capitalize;
        }

        .fc-daygrid-day {
            background: #ffffff;
            transition: background-color .2s ease;
        }

        .fc-daygrid-day:hover {
            background: #f8fafc;
        }

        .fc-daygrid-day-number {
            color: #111827;
            font-weight: 800;
            padding: 10px !important;
            font-size: 14px;
        }

        .fc-toolbar {
            gap: 12px;
            flex-wrap: wrap;
        }

        .fc-toolbar-title {
            font-size: 1.6rem !important;
            font-weight: 800;
            color: #111827;
        }

        .fc-button {
            border-radius: 12px !important;
            border: 1px solid #d1d5db !important;
            background: #ffffff !important;
            color: #374151 !important;
            box-shadow: none !important;
            padding: 0.55rem 0.9rem !important;
            font-weight: 700 !important;
        }

        .fc-button:hover,
        .fc-button.fc-button-active {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
        }

        .fc-daygrid-event {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin-top: 4px !important;
        }

        .fc-event-main {
            padding: 0 !important;
        }

        .event-pill {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            color: #fff;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            box-shadow: 0 3px 8px rgba(244, 63, 94, 0.18);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .event-pill:hover {
            filter: brightness(0.95);
            transform: translateY(-1px);
        }

        .event-pill.estado-publicado {
            background: linear-gradient(135deg, #16a34a, #10b981);
        }

        .event-pill.estado-pendiente {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
        }

        .event-pill.estado-reprogramado {
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }

        .event-pill.estado-cancelado {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .fc-daygrid-more-link {
            color: #4f46e5 !important;
            font-weight: 700;
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Vista Calendario Editorial</h1>
                        <p class="text-sm sm:text-base text-gray-600">Actividades programadas por día, semana y mes.</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/calendario-editorial') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver al listado
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6 lg:p-8">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const eventos = @json($calendarEvents);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 'auto',
                expandRows: true,
                dayMaxEvents: 4,
                displayEventTime: false,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Agenda'
                },

                events: eventos,

                eventContent: function(info) {
                    const estado = info.event.extendedProps.estado || 'pendiente';

                    return {
                        html: `
                            <a href="${info.event.url}" class="event-pill estado-${estado}">
                                ${info.event.title}
                            </a>
                        `
                    };
                },

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            });

            calendar.render();
        });
    </script>
</x-app-layout>
