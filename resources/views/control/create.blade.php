<x-app-layout>

    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">

        <h1 class="text-2xl font-bold mb-4 text-[#0c1c3c]">Nuevo Registro de Control TI</h1>

        <form method="POST" action="{{ route('control.store') }}">
            @csrf

            {{-- Actividad --}}
            <div class="mb-4">
                <label class="font-semibold">Actividad *</label>
                <input type="text" name="actividad" class="form-input w-full" required>
            </div>

            {{-- Tipo --}}
            <div class="mb-4">
                <label class="font-semibold">Tipo</label>
                <input type="text" name="tipo" class="form-input w-full"
                    placeholder="Ej: Licencia, Dominio, SSL, Soporte">
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label class="font-semibold">Descripción</label>
                <textarea name="descripcion" class="form-input w-full"></textarea>
            </div>

            {{-- Fecha de ejecución --}}
            <div class="mb-4">
                <label class="font-semibold">Fecha de Ejecución (Pago/Renovación)</label>
                <input type="date" name="fecha_ejecucion" class="form-input w-full">
            </div>

            {{-- Fecha de vencimiento --}}
            <div class="mb-4">
                <label class="font-semibold">Fecha de Vencimiento *</label>
                <input type="date" name="fecha_vencimiento" class="form-input w-full" required>
            </div>

            {{-- Días antes del recordatorio --}}
            <div class="mb-4">
                <label class="font-semibold">Días de recordatorio *</label>
                <input type="number" name="dias_recordatorio" class="form-input w-32" value="15" min="1"
                    max="90" required>
            </div>

            <button class="bg-[#0c1c3c] text-white px-4 py-2 rounded hover:bg-[#11284c]">
                Guardar Registro
            </button>

        </form>

    </div>

</x-app-layout>
