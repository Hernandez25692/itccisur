<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Planes de Trabajo TI</h1>
                <p class="mt-1 text-sm text-gray-600">Lista de planes por año, estado y actividades. Administra, revisa o crea nuevos planes.</p>
            </div>

            <div class="flex items-center gap-3">
                

                <a href="{{ route('plan-trabajo.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                    <!-- plus icon -->
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Plan
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @if ($planes->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-gray-700 mb-4">No hay planes registrados todavía.</p>
                    <a href="{{ route('plan-trabajo.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Crear primer plan</a>
                </div>
            @else
                <div class="w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Año</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividades</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($planes as $plan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $plan->anio }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $state = strtolower($plan->estado);
                                            $badgeClasses = match($state) {
                                                'activo' => 'bg-green-100 text-green-800',
                                                'borrador' => 'bg-yellow-100 text-yellow-800',
                                                'cerrado' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-blue-100 text-blue-800',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                            {{ ucfirst($plan->estado) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        <a href="{{ route('plan-trabajo.show', $plan->id) }}" class="inline-flex items-center gap-2 text-blue-600 hover:underline">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
                                            {{ $plan->actividades_count ?? 0 }} actividades
                                        </a>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('plan-trabajo.show', $plan->id) }}" class="px-3 py-1 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50">Ver</a>

                                            <a href="{{ route('plan-trabajo.edit', $plan->id) }}" class="px-3 py-1 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50">Editar</a>

                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Simple pagination (assumes $planes is LengthAwarePaginator) -->
                @if(method_exists($planes, 'links'))
                    <div class="px-4 py-3 bg-gray-50 text-right">
                        {{ $planes->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
