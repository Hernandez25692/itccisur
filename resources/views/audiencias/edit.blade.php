<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-5 space-y-4">

            <h1 class="text-lg font-bold text-gray-800">
                Editar Audiencia
            </h1>

            <form method="POST" action="{{ route('audiencias.update', $audiencia->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <label for="nombre_solicitante" class="block text-sm font-medium text-gray-700">Nombre Solicitante</label>
                <input type="text" name="nombre_solicitante" value="{{ $audiencia->nombre_solicitante }}"
                    class="w-full rounded-lg border-gray-300">

                <label for="fecha_recepcion" class="block text-sm font-medium text-gray-700">Fecha Recepción</label>
                <input type="date" name="fecha_recepcion"
                    value="{{ $audiencia->fecha_recepcion ? $audiencia->fecha_recepcion->format('Y-m-d') : '' }}"
                    class="w-full rounded-lg border-gray-300">

                <label for="fecha_hora_atencion" class="block text-sm font-medium text-gray-700">Fecha y Hora Atención</label>
                <input type="datetime-local" name="fecha_hora_atencion"
                    value="{{ $audiencia->fecha_hora_atencion ? \Carbon\Carbon::parse($audiencia->fecha_hora_atencion)->format('Y-m-d\TH:i') : '' }}"
                    class="w-full rounded-lg border-gray-300">

                <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo</label>
                <textarea name="motivo" rows="3" class="w-full rounded-lg border-gray-300">{{ $audiencia->motivo }}</textarea>

                <label for="numero_documento" class="block text-sm font-medium text-gray-700">Número Documento</label>
                <input type="text" name="numero_documento" value="{{ $audiencia->numero_documento }}"
                    class="w-full rounded-lg border-gray-300">

                <label for="dictamen" class="block text-sm font-medium text-gray-700">Dictamen</label>
                <textarea name="dictamen" rows="3" class="w-full rounded-lg border-gray-300">{{ $audiencia->dictamen }}</textarea>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('audiencias.show', $audiencia->id) }}" class="px-4 py-2 text-sm text-gray-600">
                        Cancelar
                    </a>

                    <button class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">
                        Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
