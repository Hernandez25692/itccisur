<x-app-layout>

    <div class="container">

        <h3>Editar empleado</h3>

        <form method="POST" action="{{ route('empleados.update', $empleado->id) }}">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" value="{{ $empleado->nombre_completo }}">
            </div>

            <div class="mb-3">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="{{ $empleado->correo }}">
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ $empleado->telefono }}">
            </div>

            <div class="mb-3">
                <label>Cargo</label>
                <input type="text" name="cargo" class="form-control" value="{{ $empleado->cargo }}">
            </div>

            <div class="mb-3">
                <label>Área</label>
                <input type="text" name="area" class="form-control" value="{{ $empleado->area }}">
            </div>

            <div class="mb-3">
                <label>Fecha contratación</label>
                <input type="date" name="fecha_contratacion" class="form-control"
                    value="{{ $empleado->fecha_contratacion?->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
                <label>Vacaciones acumuladas (si aplica)</label>

                <input type="number" step="0.01" name="vacaciones_acumuladas" class="form-control"
                    value="{{ $empleado->vacaciones_acumuladas }}">

                <small class="text-gray-500">
                    Ingrese días acumulados antes de iniciar el sistema
                </small>
            </div>

            <button class="btn btn-success">
                Actualizar
            </button>

        </form>

    </div>
</x-app-layout>
