<x-app-layout>

    <div class="container">

        <h2 class="text-xl font-bold mb-4">
            Editar movimiento
        </h2>

        <form method="POST" action="{{ route('vacaciones.actualizar', $movimiento->id) }}">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label>Días equivalentes</label>

                <input type="number" name="dias_equivalentes" step="0.25" class="form-control"
                    value="{{ $movimiento->dias_equivalentes }}">

            </div>

            <div class="mb-3">

                <label>Horas equivalentes</label>

                <input type="number" name="horas_equivalentes" step="0.25" class="form-control"
                    value="{{ $movimiento->horas_equivalentes }}">

            </div>

            <div class="mb-3">

                <label>Motivo</label>

                <textarea name="motivo" class="form-control">{{ $movimiento->motivo }}</textarea>

            </div>

            <button class="btn btn-success">
                Actualizar movimiento
            </button>

            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

</x-app-layout>
