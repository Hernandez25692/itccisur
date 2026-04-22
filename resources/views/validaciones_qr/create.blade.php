<x-app-layout>
    <div class="container">
        <h2>Crear Validación QR</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('validaciones_qr.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nombre de la Formación</label>
                <input type="text" name="nombre_formacion" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Fecha</label>
                <input type="date" name="fecha_formacion" class="form-control">
            </div>

            <div class="mb-3">
                <label>Año</label>
                <input type="number" name="anio" class="form-control" placeholder="Ej: 2026">
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('validaciones_qr.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</x-app-layout>
