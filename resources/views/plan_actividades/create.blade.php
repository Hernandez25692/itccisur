<x-app-layout>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-2xl font-bold mb-6">
            Nueva Actividad para el Plan {{ $plan->anio }}
        </h1>

        <form action="{{ route('plan-actividad.store', $plan->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="font-semibold">Código</label>
                    <input type="text" name="codigo" class="w-full border rounded px-3 py-2" placeholder="1.1" required>
                </div>

                <div>
                    <label class="font-semibold">Sección</label>
                    <select name="seccion" class="w-full border rounded px-3 py-2">
                        <option>Infraestructura y Seguridad</option>
                        <option>Redes y Comunicaciones</option>
                        <option>Soporte Técnico</option>
                        <option>Seguridad Informática</option>
                        <option>Sistemas y Desarrollo</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Actividad</label>
                    <input type="text" name="actividad" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Objetivo Específico</label>
                    <textarea name="objetivo" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
                </div>

                <div>
                    <label class="font-semibold">Frecuencia</label>
                    <input type="text" name="frecuencia" class="w-full border rounded px-3 py-2"
                        placeholder="Anual, Semestral, Mensual" required>
                </div>

                <div>
                    <label class="font-semibold">Responsable</label>
                    <input type="text" name="responsable" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="font-semibold">Mes previsto</label>
                    <input type="text" name="mes_previsto" class="w-full border rounded px-3 py-2"
                        placeholder="Junio, Octubre, etc.">
                </div>

                <div>
                    <label class="font-semibold">Fecha a ejecutar</label>
                    <input type="text" name="fecha_ejecucion" class="w-full border rounded px-3 py-2"
                        placeholder="20–29/10/2026">
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Métrica de Éxito</label>
                    <input type="text" name="metrica_exito" class="w-full border rounded px-3 py-2">
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Observaciones</label>
                    <textarea name="observaciones" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                </div>

            </div>

            <button class="mt-5 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Guardar Actividad
            </button>

        </form>

    </div>

</x-app-layout>
