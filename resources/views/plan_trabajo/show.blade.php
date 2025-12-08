<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Encabezado del Plan -->
        <div class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] rounded-2xl p-8 text-white shadow-xl">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-white/10 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Plan de Trabajo TI {{ $plan->anio }}</h1>
                            <p class="text-gray-300 mt-1">Gestión estratégica de actividades tecnológicas</p>
                        </div>
                    </div>
                    
                    <!-- Estado del Plan -->
                    <div class="flex flex-wrap items-center gap-4 mt-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                            @if($plan->estado == 'aprobado') bg-green-100 text-green-800
                            @elseif($plan->estado == 'enviado') bg-blue-100 text-blue-800
                            @elseif($plan->estado == 'rechazado') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($plan->estado == 'aprobado')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($plan->estado == 'enviado')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            @elseif($plan->estado == 'rechazado')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @endif
                            {{ ucfirst($plan->estado) }}
                        </div>
                        
                        @if($plan->fecha_aprobacion)
                            <div class="flex items-center gap-2 text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Revisado el {{ optional($plan->fecha_aprobacion)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Acciones del Plan -->
                <div class="flex flex-wrap gap-3">
                    @role('admin_ti')
                        @if($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                            <form action="{{ route('plan-trabajo.enviar', $plan) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Enviar a Gerencia
                                </button>
                            </form>
                            
                            @if($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                                <a href="{{ route('plan-trabajo.edit', $plan) }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-sm text-white border border-white/20 rounded-xl hover:bg-white/20 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar Plan
                                </a>
                            @endif
                        @endif
                    @endrole
                </div>
            </div>
        </div>

        <!-- Descripción General -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-gradient-to-r from-[#0C1C3C]/10 to-[#1A2A4F]/10 rounded-xl">
                    <svg class="w-6 h-6 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Descripción General</h2>
            </div>
            
            <div class="prose prose-lg max-w-none">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <p class="text-gray-700 leading-relaxed">
                        {{ $plan->descripcion_general ?: 'Sin descripción general registrada.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Actividades del Plan -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                            <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Actividades del Plan</h2>
                            <p class="text-gray-600">Total: {{ $plan->actividades->count() }} actividades registradas</p>
                        </div>
                    </div>
                    
                    @role('admin_ti')
                        @if($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                            <a href="{{ route('plan-actividad.create', $plan->id) }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Agregar Actividad
                            </a>
                        @endif
                    @endrole
                </div>

                @if($plan->actividades->count() == 0)
                    <div class="py-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay actividades registradas</h3>
                        <p class="text-gray-600">Comienza agregando la primera actividad al plan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">ID</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">Actividad</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">Responsable</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">Fecha</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">Estado</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">Avance</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 text-sm">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($plan->actividades as $act)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#0C1C3C]/10 text-[#0C1C3C]">
                                                {{ $act->codigo }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $act->actividad }}</p>
                                                <p class="text-sm text-gray-500 mt-1">{{ $act->objetivo }}</p>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-[#C5A049] to-[#D8B96E] flex items-center justify-center text-[#0C1C3C] text-xs font-bold">
                                                    {{ strtoupper(substr($act->responsable ?? '?', 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $act->responsable }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900">{{ $act->fecha_ejecucion }}</div>
                                                <div class="text-gray-500">{{ $act->mes_previsto }}</div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($act->progreso == 100) bg-green-100 text-green-800
                                                @elseif($act->estado == 'en_proceso') bg-blue-100 text-blue-800
                                                @elseif($act->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($act->progreso == 100)
                                                    Completado
                                                @else
                                                    {{ ucfirst($act->estado) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="space-y-2 w-32">
                                                <div class="flex justify-between text-xs">
                                                    <span class="font-medium">{{ $act->progreso }}%</span>
                                                </div>
                                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full
                                                        @if($act->progreso == 100) bg-green-500
                                                        @elseif($act->progreso > 0) bg-yellow-500
                                                        @else bg-gray-400 @endif"
                                                         style="width: {{ $act->progreso }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <a href="{{ route('plan-actividad.show', $act->id) }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white text-sm font-medium rounded-xl hover:shadow-md transition-shadow">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver / Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Revisión de Gerencia -->
        @role('gerencia')
            @if($plan->estado === 'enviado')
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                            <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Revisión y Aprobación de Gerencia</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Aprobar -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                            <form action="{{ route('plan-trabajo.aprobar', $plan) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <label class="block font-semibold text-green-800 mb-3">Comentario de Aprobación (opcional)</label>
                                    <textarea name="comentario_gerencia" rows="4"
                                              class="w-full border border-green-300 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-colors"
                                              placeholder="Agrega comentarios o sugerencias...">{{ old('comentario_gerencia') }}</textarea>
                                </div>
                                <button class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Aprobar Plan
                                </button>
                            </form>
                        </div>
                        
                        <!-- Rechazar -->
                        <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-200 rounded-xl p-6">
                            <form action="{{ route('plan-trabajo.rechazar', $plan) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <label class="block font-semibold text-red-800 mb-3">
                                        Comentario de Rechazo
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="comentario_gerencia" rows="4" required
                                              class="w-full border border-red-300 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-colors"
                                              placeholder="Explica las razones del rechazo..."></textarea>
                                </div>
                                <button class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rechazar Plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endrole

        <!-- Comentario de Gerencia -->
        @if($plan->comentario_gerencia && $plan->estado !== 'enviado')
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-gradient-to-r from-[#1A2A4F]/10 to-[#0C1C3C]/10 rounded-xl">
                        <svg class="w-6 h-6 text-[#1A2A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Comentario de Gerencia</h2>
                </div>
                
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6">
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $plan->comentario_gerencia }}</p>
                </div>
            </div>
        @endif

        <!-- Historial de Revisiones -->
        @if($plan->revisiones->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                        <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Historial de Revisiones</h2>
                </div>
                
                <div class="space-y-4">
                    @foreach($plan->revisiones as $rev)
                        <div class="border border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold
                                            @if($rev->accion == 'aprobado') bg-green-100 text-green-800
                                            @elseif($rev->accion == 'rechazado') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($rev->accion) }}
                                        </span>
                                        <span class="text-sm text-gray-600">{{ $rev->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="font-semibold text-gray-900">
                                        Por {{ $rev->usuario->name ?? 'Usuario eliminado' }}
                                    </p>
                                    @if($rev->comentario)
                                        <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                                            <p class="text-gray-700">{{ $rev->comentario }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>