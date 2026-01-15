<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Crear Ruta de Cobro</h1>
            <a href="{{ route('cobranza.rutas.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cobranza.rutas.store') }}"
            class="bg-white rounded-2xl border p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Nombre de la ruta</label>
                    <input name="nombre" required class="w-full rounded-xl border-gray-300"
                        placeholder="Ej: Ruta Centro - Lunes">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Fecha</label>
                    <input type="date" name="fecha_ruta" value="{{ now()->toDateString() }}"
                        class="w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Gestor (user_id)</label>
                    <input name="gestor_id" required class="w-full rounded-xl border-gray-300"
                        placeholder="ID del gestor">
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Comentario</label>
                <textarea name="comentario" rows="2" class="w-full rounded-xl border-gray-300"
                    placeholder="Observaciones de la ruta"></textarea>
            </div>

            <div class="border-t pt-6">
                <h2 class="font-bold text-gray-900 mb-2">Seleccionar Empresas</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Tip: selecciona empresas cercanas para optimizar la ruta.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[400px] overflow-y-auto">
                    @foreach ($empresas as $e)
                        <label class="flex items-center gap-3 p-3 rounded-xl border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="empresa_ids[]" value="{{ $e->id }}"
                                class="rounded border-gray-300">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $e->nombre_empresa }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $e->ciudad }} · {{ $e->barrio_colonia }}
                                </p>
                                <p
                                    class="text-xs {{ $e->estatus_cobranza === 'en_mora' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ strtoupper($e->estatus_cobranza) }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('cobranza.rutas.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Cancelar
                </a>
                <button class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    Crear Ruta
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
