<x-app-layout>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-2xl font-bold mb-6">Crear Plan de Trabajo TI</h1>

        <form action="{{ route('plan-trabajo.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Año del Plan</label>
                <input type="number" name="anio" class="w-full border rounded px-3 py-2" value="{{ date('Y') }}"
                    min="2024" max="2100" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Descripción General</label>
                <textarea name="descripcion_general" rows="4" class="w-full border rounded px-3 py-2"
                    placeholder="Ejemplo: Plan anual de infraestructura, seguridad, soporte técnico y modernización tecnológica."></textarea>
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Guardar Plan
            </button>
        </form>
    </div>

</x-app-layout>
