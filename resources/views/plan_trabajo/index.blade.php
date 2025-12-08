<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Principal -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C] mb-2">Planes de Trabajo TI</h1>
                    <p class="text-gray-600">Lista de planes por año, estado y actividades. Administra, revisa o crea nuevos planes.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    
                    
                    <a href="{{ route('plan-trabajo.create') }}" 
                       class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Plan
                    </a>
                </div>
            </div>
        </div>

        <!-- Panel de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Planes Aprobados</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ $planes->where('estado', 'aprobado')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">En Borrador</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">
                            {{ $planes->where('estado', 'borrador')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-xl">
                        <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Planes Rechazados</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">
                            {{ $planes->where('estado', 'rechazado')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-xl">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Tabla de Planes -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            @if ($planes->isEmpty())
                <!-- Estado vacío -->
                <div class="py-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay planes registrados</h3>
                    <p class="text-gray-600 mb-6">Comienza creando el primer plan de trabajo anual.</p>
                    <a href="{{ route('plan-trabajo.create') }}"
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear primer plan
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white">
                                <th class="p-4 text-left font-semibold text-sm">Año</th>
                                <th class="p-4 text-left font-semibold text-sm">Estado</th>
                                <th class="p-4 text-left font-semibold text-sm">Actividades</th>
                                <th class="p-4 text-left font-semibold text-sm">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($planes as $plan)
                                @php
                                    $state = strtolower($plan->estado);
                                    $badgeClasses = match($state) {
                                        'borrador' => 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
                                        'enviado' => 'bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border border-blue-200',
                                        'aprobado' => 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                        'rechazado' => 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
                                        default => 'bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700 border border-gray-200',
                                    };
                                    
                                    $iconClasses = match($state) {
                                        'borrador' => 'text-yellow-600',
                                        'enviado' => 'text-blue-600',
                                        'aprobado' => 'text-green-600',
                                        'rechazado' => 'text-red-600',
                                        default => 'text-gray-600',
                                    };
                                @endphp
                                
                                <tr class="hover:bg-gray-50/80 transition-colors duration-200">
                                    <!-- Año -->
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                {{ substr($plan->anio, -2) }}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900">{{ $plan->anio }}</h3>
                                                <p class="text-sm text-gray-500">Plan Anual TI</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Estado -->
                                    <td class="p-4">
                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold {{ $badgeClasses }}">
                                            @if($state == 'borrador')
                                                <svg class="w-4 h-4 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            @elseif($state == 'enviado')
                                                <svg class="w-4 h-4 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            @elseif($state == 'aprobado')
                                                <svg class="w-4 h-4 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($state == 'rechazado')
                                                <svg class="w-4 h-4 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                            {{ ucfirst($plan->estado) }}
                                        </div>
                                    </td>
                                    
                                    <!-- Actividades -->
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-50 rounded-lg">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <a href="{{ route('plan-trabajo.show', $plan->id) }}" 
                                                   class="text-lg font-bold text-gray-900 hover:text-[#C5A049] transition-colors">
                                                    {{ $plan->actividades_count ?? 0 }}
                                                </a>
                                                <p class="text-sm text-gray-500">actividades</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Acciones -->
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Ver -->
                                            <a href="{{ route('plan-trabajo.show', $plan->id) }}"
                                               class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white text-sm font-medium rounded-xl hover:shadow-md transition-shadow duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                            
                                            <!-- Editar -->
                                            <a href="{{ route('plan-trabajo.edit', $plan->id) }}"
                                               class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] text-sm font-medium rounded-xl hover:shadow-md transition-shadow duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if(method_exists($planes, 'links'))
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-700">
                                Mostrando 
                                <span class="font-medium">{{ $planes->firstItem() }}</span>
                                a 
                                <span class="font-medium">{{ $planes->lastItem() }}</span>
                                de 
                                <span class="font-medium">{{ $planes->total() }}</span>
                                planes
                            </div>
                            <div class="flex items-center space-x-2">
                                {{ $planes->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>