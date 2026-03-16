<x-app-layout>

    <div class="container">

        <h2 class="text-xl font-bold mb-4">
            Registrar Vacaciones - {{ $empleado->nombre_completo }}
        </h2>

        <form method="POST" action="{{ route('vacaciones.guardar') }}" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="empleado_id" value="{{ $empleado->id }}">

            <div class="mb-3">
                <label>Tipo</label>

                <select name="tipo_movimiento" id="tipo_movimiento" class="form-control">
                    <option value="vacacion_dias">Vacaciones por días</option>
                    <option value="vacacion_horas">Vacaciones por horas</option>
                    <option value="permiso_especial">Permiso especial</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Fecha inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
            </div>

            <div class="mb-3">
                <label>Fecha fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
            </div>

            <div class="mb-3">
                <label>Hora inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control">
            </div>

            <div class="mb-3">
                <label>Hora fin</label>
                <input type="time" name="hora_fin" id="hora_fin" class="form-control">
            </div>

            <div class="mb-3">
                <label>Motivo</label>
                <textarea name="motivo" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Comprobante</label>
                <input type="file" name="archivo" class="form-control">
            </div>

            <div class="alert alert-info">

                <strong>Resumen de solicitud</strong>

                <div id="resultadoSolicitud">
                    Seleccione fechas u horas para calcular.
                </div>

            </div>
            <div class="mb-3">

                <label>Días a descontar (ajuste manual)</label>

                <input type="number" name="dias_ajuste" step="0.25" class="form-control"
                    placeholder="Opcional - solo si desea modificar el cálculo automático">

                <small class="text-muted">
                    Si se llena este campo, reemplaza el cálculo automático.
                </small>

            </div>
            <button class="btn btn-success">
                Guardar solicitud
            </button>

        </form>

    </div>


    <script>
        function calcularSolicitud() {

            let tipo = document.getElementById('tipo_movimiento').value;

            let fechaInicio = document.getElementById('fecha_inicio').value;
            let fechaFin = document.getElementById('fecha_fin').value;

            let horaInicio = document.getElementById('hora_inicio').value;
            let horaFin = document.getElementById('hora_fin').value;

            let resultado = document.getElementById('resultadoSolicitud');

            if (tipo === "vacacion_dias") {

                if (fechaInicio && fechaFin) {

                    let inicio = new Date(fechaInicio);
                    let fin = new Date(fechaFin);

                    let diferencia = (fin - inicio) / (1000 * 60 * 60 * 24) + 1;

                    resultado.innerHTML = "Días solicitados: <strong>" + diferencia + "</strong>";

                }

            }

            if (tipo === "vacacion_horas" || tipo === "permiso_especial") {

                if (horaInicio && horaFin) {

                    let h1 = horaInicio.split(":");
                    let h2 = horaFin.split(":");

                    let inicio = parseInt(h1[0]) + parseInt(h1[1]) / 60;
                    let fin = parseInt(h2[0]) + parseInt(h2[1]) / 60;

                    let horas = fin - inicio;

                    if (horas < 0) horas = 0;

                    let dias = horas / 8;

                    resultado.innerHTML =
                        "Horas solicitadas: <strong>" + horas.toFixed(2) + "</strong><br>" +
                        "Equivalente en días: <strong>" + dias.toFixed(2) + "</strong>";

                }

            }

        }

        document.getElementById('fecha_inicio').addEventListener('change', calcularSolicitud);
        document.getElementById('fecha_fin').addEventListener('change', calcularSolicitud);
        document.getElementById('hora_inicio').addEventListener('change', calcularSolicitud);
        document.getElementById('hora_fin').addEventListener('change', calcularSolicitud);
        document.getElementById('tipo_movimiento').addEventListener('change', calcularSolicitud);
    </script>



</x-app-layout>
