<x-app-layout>

    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">

        <h2 class="text-xl font-bold mb-4">Registrar Actividad</h2>

        <form method="POST" action="{{ route('bitacora.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="font-semibold">Título *</label>
                <input type="text" name="titulo" class="form-input w-full" required>
            </div>

            <div class="mb-3">
                <label class="font-semibold">Descripción</label>
                <textarea name="descripcion" class="form-input w-full"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label>Equipo afectado</label>
                    <input type="text" name="equipo_afectado" class="form-input w-full">
                </div>

                <div>
                    <label>Tipo de falla</label>
                    <input type="text" name="tipo_falla" class="form-input w-full">
                </div>
            </div>

            <div class="mb-3">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" class="form-input w-full">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label>Fecha *</label>
                    <input type="date" name="fecha" class="form-input w-full" required>
                </div>
                <div>
                    <label>Prioridad *</label>
                    <select name="prioridad" class="form-input w-full">
                        <option value="baja">Baja</option>
                        <option value="media" selected>Media</option>
                        <option value="alta">Alta</option>
                        <option value="critica">Crítica</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio" class="form-input w-full">
                </div>
                <div>
                    <label>Hora fin</label>
                    <input type="time" name="hora_fin" class="form-input w-full">
                </div>
            </div>

            <div class="mb-3">
                <label>Solución aplicada</label>
                <textarea name="solucion_aplicada" class="form-input w-full"></textarea>
            </div>

            <div class="mb-3">
                <label>Evidencia (opcional)</label>
                <input type="file" name="evidencia" accept="image/*">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Guardar actividad
            </button>

        </form>

    </div>
</x-app-layout>