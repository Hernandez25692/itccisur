<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-5 space-y-4">

            <h1 class="text-lg font-bold text-gray-800">
                Registrar Audiencia
            </h1>

            <form method="POST" action="{{ route('audiencias.store') }}" class="space-y-4">
                @csrf

                <label for="nombre_solicitante" class="block text-sm font-medium text-gray-700">Nombre de quien solicita</label>
                <input type="text" id="nombre_solicitante" name="nombre_solicitante" required class="w-full rounded-lg border-gray-300" placeholder="Nombre de quien solicita">

                <label for="fecha_recepcion" class="block text-sm font-medium text-gray-700">Fecha de recepción</label>
                <input type="date" id="fecha_recepcion" name="fecha_recepcion" required class="w-full rounded-lg border-gray-300">

                <label for="fecha_hora_atencion" class="block text-sm font-medium text-gray-700">Fecha y hora de atención</label>
                <input type="datetime-local" id="fecha_hora_atencion" name="fecha_hora_atencion" class="w-full rounded-lg border-gray-300">

                <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo</label>
                <textarea id="motivo" name="motivo" rows="3" required class="w-full rounded-lg border-gray-300" placeholder="Motivo"></textarea>

                <label for="numero_documento" class="block text-sm font-medium text-gray-700">Nª Presentaciòn/Matricula/Asiento/Tomo</label>
                <input type="text" id="numero_documento" name="numero_documento" required class="w-full rounded-lg border-gray-300" placeholder="Número de presentaciòn/matricula/asiento/tomo">

                <label for="dictamen" class="block text-sm font-medium text-gray-700">Dictamen (opcional)</label>
                <textarea id="dictamen" name="dictamen" rows="3" class="w-full rounded-lg border-gray-300" placeholder="Dictamen (opcional)"></textarea>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('audiencias.index') }}" class="px-4 py-2 text-sm text-gray-600">
                        Cancelar
                    </a>

                    <button class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">
                        Guardar
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
