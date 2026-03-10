<x-app-layout>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<div class="ccisur-page">
    <div class="ccisur-wrapper">

        <div class="ccisur-header">
            <div class="ccisur-header-left">
                <h1>Calendario <span>Editorial</span></h1>
                <p>Cámara de Comercio e Industria del Sur · CCISUR</p>
            </div>
        </div>

        <div class="ccisur-card">
            <div id="calendar"></div>
        </div>

    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        locale: 'es',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },

        events: [

            @foreach ($eventos as $evento)

            {
                id: "{{ $evento->id }}",
                title: "{!! addslashes($evento->tema) !!}",
                start: "{{ $evento->fecha_publicacion }}",
                url: "{{ route('calendario-editorial.show',$evento->id) }}"
            },

            @endforeach

        ],

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            window.location.href = info.event.url;
        }

    });

    calendar.render();

});

</script>

</x-app-layout>