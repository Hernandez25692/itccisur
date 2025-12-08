<x-app-layout>

    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">

        <h1 class="text-2xl font-bold mb-4 text-[#0c1c3c]">
            Editar Registro de Control TI
        </h1>

        <form method="POST" action="{{ route('control.update', $recordatorio->id) }}">
            @csrf
            @method('PUT')

            {{-- Actividad --}}
            <div class="mb-4">
                <label class="font-semibold">Actividad *</label>
                <input type="text" name="actividad" value="{{ old('actividad', $recordatorio->actividad) }}"
                    class="form-input w-full" required>
            </div>

            {{-- Tipo --}}
            <div class="mb-4">
                <label class="font-semibold">Tipo</label>
                <input type="text" name="tipo" value="{{ old('tipo', $recordatorio->tipo) }}"
                    class="form-input w-full">
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label class="font-semibold">Descripción</label>
                <textarea name="descripcion" class="form-input w-full">{{ old('descripcion', $recordatorio->descripcion) }}</textarea>
            </div>

            {{-- Fecha de ejecución --}}
            <div class="mb-4">
                <label class="font-semibold">Fecha de Ejecución</label>
                <input type="date" name="fecha_ejecucion"
                    value="{{ $recordatorio->fecha_ejecucion ? \Carbon\Carbon::parse($recordatorio->fecha_ejecucion)->format('Y-m-d') : '' }}"
                    class="form-input w-full">

            </div>

            {{-- Fecha de vencimiento --}}
            <div class="mb-4">
                <label class="font-semibold">Fecha de Vencimiento *</label>
                <input type="date" name="fecha_vencimiento"
                    value="{{ $recordatorio->fecha_vencimiento ? \Carbon\Carbon::parse($recordatorio->fecha_vencimiento)->format('Y-m-d') : '' }}"
                    class="form-input w-full"
                    required>
            </div>

            {{-- Días antes del recordatorio --}}
            <div class="mb-4">
                <label class="font-semibold">Días de recordatorio *</label>
                <input type="number" name="dias_recordatorio"
                    value="{{ old('dias_recordatorio', $recordatorio->dias_recordatorio) }}" class="form-input w-32"
                    min="1" max="90" required>
            </div>

            {{-- Notificar --}}
            <div class="mb-4">
                <label class="font-semibold">¿Notificar?</label>
                <input type="checkbox" name="notificar" value="1" {{ $recordatorio->notificar ? 'checked' : '' }}>
            </div>

            {{-- Atendido --}}
            <div class="mb-4">
                <label class="font-semibold">¿Atendido?</label>
                <input type="checkbox" name="atendido" value="1" {{ $recordatorio->atendido ? 'checked' : '' }}>
            </div>

            <button class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                Actualizar Registro
            </button>

        </form>

    </div>

</x-app-layout>
