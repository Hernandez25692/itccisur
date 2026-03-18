<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        {{-- Título --}}
        <h3 class="text-2xl font-semibold mb-6 text-[#C5A049]">Editar empleado</h3>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('empleados.update', $empleado->id) }}"
            class="bg-[#1e293b] p-6 rounded shadow-md max-w-4xl mx-auto">
            @csrf
            @method('PUT')

            {{-- Grid de dos columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nombre completo --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-[#C5A049] font-medium">Nombre completo</label>
                    <input type="text" name="nombre_completo" value="{{ $empleado->nombre_completo }}"
                        class="px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                </div>

                {{-- Correo --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-[#C5A049] font-medium">Correo</label>
                    <input type="email" name="correo" value="{{ $empleado->correo }}"
                        class="px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                </div>

                {{-- Teléfono --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-[#C5A049] font-medium">Teléfono</label>
                    <input type="text" name="telefono" value="{{ $empleado->telefono }}"
                        class="px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                </div>

                {{-- Cargo --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-[#C5A049] font-medium">Cargo</label>
                    <input type="text" name="cargo" value="{{ $empleado->cargo }}"
                        class="px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                </div>

                {{-- Área --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-[#C5A049] font-medium">Área</label>
                    <input type="text" name="area" value="{{ $empleado->area }}"
                        class="px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                </div>

                {{-- Fecha contratación --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-[#C5A049] font-medium">Fecha contratación</label>
                    <input type="date" name="fecha_contratacion"
                        value="{{ $empleado->fecha_contratacion?->format('Y-m-d') }}"
                        class="px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                </div>

                {{-- Vacaciones acumuladas --}}
                <div class="flex flex-col md:col-span-2">
                    <label class="mb-1 text-[#C5A049] font-medium">Vacaciones acumuladas (si aplica)</label>
                    <input type="number" step="0.01" name="vacaciones_acumuladas"
                        value="{{ $empleado->vacaciones_acumuladas }}"
                        class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                    <small class="text-gray-400 mt-1">Ingrese días acumulados antes de iniciar el sistema</small>
                </div>

            </div>

            {{-- Botón actualizar --}}
            <div class="mt-6">
                <button type="submit"
                    class="bg-[#C5A049] hover:bg-yellow-600 text-[#0f172a] font-semibold px-6 py-2 rounded shadow-md">
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
