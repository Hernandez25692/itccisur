<x-app-layout>

    <div class="max-w-6xl mx-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Planes de Trabajo TI</h1>

            <a href="{{ route('plan-trabajo.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuevo Plan
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Año</th>
                        <th class="p-2 border">Estado</th>
                        <th class="p-2 border">Actividades</th>
                        <th class="p-2 border">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($planes as $plan)
                        <tr>
                            <td class="p-2 border">{{ $plan->anio }}</td>
                            <td class="p-2 border capitalize">{{ $plan->estado }}</td>
                            <td class="p-2 border">{{ $plan->actividades_count }}</td>
                            <td class="p-2 border">
                                <a class="text-blue-600" href="{{ route('plan-trabajo.show', $plan->id) }}">Ver</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
