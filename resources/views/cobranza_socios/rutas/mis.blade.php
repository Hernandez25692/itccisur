<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mis rutas</h1>
            <p class="text-sm text-gray-500">Solo rutas donde tienes empresas asignadas.</p>
        </div>

        <div class="bg-white rounded-2xl border overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($rutas as $r)
                        <tr>
                            <td class="px-4 py-3">{{ $r->fecha_ruta->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $r->nombre }}</td>
                            <td class="px-4 py-3">{{ strtoupper($r->estado) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('cobranza.rutas.mi_ruta', $r) }}"
                                    class="px-3 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                                    Ver mi ruta
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-gray-400">No tienes rutas asignadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $rutas->links() }}</div>
    </div>
</x-app-layout>
