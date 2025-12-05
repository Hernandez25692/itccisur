<x-app-layout>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-2xl font-bold mb-6">Editar Plan: {{ $plan->anio }}</h1>

        <form action="{{ route('plan-trabajo.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Año del Plan</label>
                <input type="number" name="anio" class="w-full border rounded px-3 py-2" value="{{ $plan->anio }}"
                    required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Descripción General</label>
                <textarea name="descripcion_general" rows="4" class="w-full border rounded px-3 py-2">{{ $plan->descripcion_general }}</textarea>
            </div>

            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Guardar Cambios
            </button>

        </form>
    </div>

</x-app-layout>
