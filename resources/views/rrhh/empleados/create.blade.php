<x-app-layout>

    <div class="container">

        <h3>Registrar nuevo empleado</h3>

        <form method="POST" action="{{ route('empleados.store') }}">

            @csrf

            <div class="mb-3">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Identidad</label>
                <input type="text" name="identidad" class="form-control">
            </div>

            <div class="mb-3">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control">
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>

            <div class="mb-3">
                <label>Cargo</label>
                <input type="text" name="cargo" class="form-control">
            </div>

            <div class="mb-3">
                <label>Área</label>
                <input type="text" name="area" class="form-control">
            </div>

            <div class="mb-3">
                <label>Fecha de contratación</label>
                <input type="date" name="fecha_contratacion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Vacaciones acumuladas (si aplica)</label>

                <input type="number" step="0.01" name="vacaciones_acumuladas" class="form-control" value="0">

                <small class="text-gray-500">
                    Ingrese días acumulados antes de iniciar el sistema
                </small>

            </div>

            <button class="btn btn-success">
                Guardar empleado
            </button>

        </form>

    </div>
</x-app-layout>
