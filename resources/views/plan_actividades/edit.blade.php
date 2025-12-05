<x-app-layout>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">

        <h1 class="text-2xl font-bold mb-6">Editar Actividad {{ $actividad->codigo }}</h1>

        <form method="POST" action="{{ route('plan-actividad.update', $actividad->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="font-semibold">Código</label>
                    <input type="text" name="codigo" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->codigo }}" required>
                </div>

                <div>
                    <label class="font-semibold">Sección</label>
                    <input type="text" name="seccion" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->seccion }}" required>
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Actividad</label>
                    <input type="text" name="actividad" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->actividad }}" required>
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Objetivo</label>
                    <textarea name="objetivo" rows="3" class="w-full border rounded px-3 py-2" required>{{ $actividad->objetivo }}</textarea>
                </div>

                <div>
                    <label class="font-semibold">Frecuencia</label>
                    <input type="text" name="frecuencia" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->frecuencia }}">
                </div>

                <div>
                    <label class="font-semibold">Responsable</label>
                    <input type="text" name="responsable" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->responsable }}">
                </div>

                <div>
                    <label class="font-semibold">Mes previsto</label>
                    <input type="text" name="mes_previsto" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->mes_previsto }}">
                </div>

                <div>
                    <label class="font-semibold">Fecha de ejecución</label>
                    <input type="text" name="fecha_ejecucion" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->fecha_ejecucion }}">
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Métrica de éxito</label>
                    <input type="text" name="metrica_exito" class="w-full border rounded px-3 py-2"
                        value="{{ $actividad->metrica_exito }}">
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Observaciones</label>
                    <textarea name="observaciones" rows="2" class="w-full border rounded px-3 py-2">{{ $actividad->observaciones }}</textarea>
                </div>

                <div>
                    <label class="font-semibold">Estado</label>
                    <select name="estado" class="w-full border rounded px-3 py-2">
                        <option value="pendiente" {{ $actividad->estado == 'pendiente' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="en_progreso" {{ $actividad->estado == 'en_progreso' ? 'selected' : '' }}>En
                            progreso</option>
                        <option value="finalizado" {{ $actividad->estado == 'finalizado' ? 'selected' : '' }}>
                            Finalizado</option>
                    </select>
                </div>

            </div>

            <button class="mt-6 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Guardar Cambios
            </button>

        </form>

    </div>

</x-app-layout>
