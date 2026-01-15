<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Categorías de Empresas</h1>
        </div>

        @if (session('success'))
            <div class="p-3 rounded-xl bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('cobranza.categorias.store') }}"
            class="bg-white rounded-2xl border p-6 space-y-4">
            @csrf
            <h2 class="font-semibold text-gray-900">Nueva Categoría</h2>

            <input name="nombre" required class="w-full rounded-xl border-gray-300"
                placeholder="Ej: Comercio, Servicios, Industria">

            <textarea name="descripcion" rows="2" class="w-full rounded-xl border-gray-300"
                placeholder="Descripción (opcional)"></textarea>

            <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                Guardar
            </button>
        </form>

        <div class="bg-white rounded-2xl border overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($categorias as $c)
                        <tr>
                            <td class="p-3 font-semibold">{{ $c->nombre }}</td>
                            <td class="p-3 text-center">
                                {{ $c->activo ? 'Activo' : 'Inactivo' }}
                            </td>
                            <td class="p-3 text-right">
                                <form method="POST" action="{{ route('cobranza.categorias.update', $c) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="activo" value="{{ !$c->activo }}">
                                    <button class="px-3 py-1 rounded-xl border hover:bg-gray-50">
                                        {{ $c->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
