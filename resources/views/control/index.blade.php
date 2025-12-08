<x-app-layout>
    <div class="max-w-7xl mx-auto py-8">
        <!-- Header Principal -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C] mb-2">
                        Control de Licencias, Dominios y Servicios TI
                    </h1>
                    <p class="text-gray-600">Gestión centralizada de activos tecnológicos y renovaciones</p>
                </div>

                <a href="{{ route('control.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Registro
                </a>
            </div>
        </div>

        <!-- Mensajes de éxito -->
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Panel de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] text-white p-6 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300">Total Registros</p>
                        <p class="text-3xl font-bold mt-2">{{ $recordatorios->count() }}</p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pendientes</p>
                        <p class="text-3xl font-bold text-orange-600 mt-2">
                            {{ $recordatorios->where('atendido', false)->where('fecha_vencimiento', '>=', now())->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-xl">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Vencidos</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">
                            {{ $recordatorios->where('atendido', false)->where('fecha_vencimiento', '<', now())->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-xl">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Atendidos</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ $recordatorios->where('atendido', true)->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de Vencimiento -->
        @if ($alertas->count() > 0)
            <div
                class="mb-8 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Alertas de Vencimiento</h2>
                            <p class="text-sm text-gray-600">Próximos 15 días o vencidos recientemente</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($alertas as $alerta)
                            @php
                                $hoy = now();
                                $vence = \Carbon\Carbon::parse($alerta->fecha_vencimiento);
                                $dias = (int)$hoy->diffInDays($vence, false);
                                $urgente = $dias <= 3;
                            @endphp

                            <div
                                class="bg-white p-4 rounded-xl border {{ $urgente ? 'border-red-200' : 'border-orange-200' }} shadow-sm">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900">{{ $alerta->actividad }}</h3>
                                    @if ($urgente)
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                                            URGENTE
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $alerta->tipo ?? 'Sin tipo especificado' }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm {{ $urgente ? 'text-red-600 font-bold' : 'text-orange-600' }}">
                                        {{ $dias < 0 ? 'Vencido hace ' . abs($dias) . ' días' : 'Vence en ' . $dias . ' días' }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $vence->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        

        <!-- Tabla Principal -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white">
                            <th class="p-4 text-left font-semibold text-sm">Actividad</th>
                            <th class="p-4 text-left font-semibold text-sm">Tipo</th>
                            <th class="p-4 text-left font-semibold text-sm">Fecha Ejecución</th>
                            <th class="p-4 text-left font-semibold text-sm">Vence</th>
                            <th class="p-4 text-center font-semibold text-sm">Estado</th>
                            <th class="p-4 text-center font-semibold text-sm">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recordatorios as $item)
                            @php
                                $hoy = now();
                                $vence = \Carbon\Carbon::parse($item->fecha_vencimiento);
                                $dias = $hoy->diffInDays($vence, false);
                                $alerta_15 = $dias > 0 && $dias <= $item->dias_recordatorio;
                                $urgente = $dias <= 3 && $dias >= 0;
                            @endphp

                            <tr class="hover:bg-gray-50/80 transition-colors duration-200">
                                <!-- Actividad -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-[#0C1C3C]/10 to-[#1A2A4F]/10">
                                            @if (str_contains(strtolower($item->tipo), 'licencia'))
                                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                    </path>
                                                </svg>
                                            @elseif(str_contains(strtolower($item->tipo), 'dominio'))
                                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-[#0C1C3C]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->actividad }}</p>
                                            @if ($item->descripcion)
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ Str::limit($item->descripcion, 40) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Tipo -->
                                <td class="p-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if (str_contains(strtolower($item->tipo), 'licencia')) bg-blue-100 text-blue-800
                                        @elseif(str_contains(strtolower($item->tipo), 'dominio')) bg-purple-100 text-purple-800
                                        @elseif(str_contains(strtolower($item->tipo), 'servicio')) bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $item->tipo ?? '—' }}
                                    </span>
                                </td>

                                <!-- Fecha Ejecución -->
                                <td class="p-4">
                                    @if ($item->fecha_ejecucion)
                                        <div class="text-gray-700">
                                            {{ \Carbon\Carbon::parse($item->fecha_ejecucion)->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($item->fecha_ejecucion)->diffForHumans() }}</div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <!-- Fecha Vencimiento -->
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <div class="font-medium text-gray-900">{{ $vence->format('d/m/Y') }}</div>
                                        <div
                                            class="text-sm {{ $dias < 0 ? 'text-red-600' : ($urgente ? 'text-orange-600' : 'text-gray-500') }}">
                                            @if ($dias < 0)
                                                Vencido hace {{ abs((int)$dias) }} días
                                            @else
                                                {{ (int)$dias }} días restantes
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="p-4">
                                    <div class="flex justify-center">
                                        @if ($item->atendido)
                                            <span
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Atendido
                                            </span>
                                        @else
                                            @if ($dias < 0)
                                                <span
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                        </path>
                                                    </svg>
                                                    Vencido
                                                </span>
                                            @elseif($dias == 0)
                                                <span
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    ¡Vence hoy!
                                                </span>
                                            @elseif($urgente)
                                                <span
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 border border-orange-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.406 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                        </path>
                                                    </svg>
                                                    Urgente ({{ (int)$dias }} días)
                                                </span>
                                            @elseif($alerta_15)
                                                <span
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border border-blue-200">
                                                    ⚠️ {{ (int)$dias }} días
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700 border border-gray-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Vigente ({{ (int)$dias }} días)
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('control.edit', $item->id) }}"
                                            class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white text-sm font-medium rounded-xl hover:shadow-md transition-shadow duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Editar
                                        </a>

                                        @role('admin_ti')
                                            <form action="{{ route('control.destroy', $item->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirmDelete(event, '{{ addslashes($item->actividad) }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-xl hover:shadow-md transition-shadow duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío -->
            @if ($recordatorios->isEmpty())
                <div class="py-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay registros disponibles</h3>
                    <p class="text-gray-600 mb-6">Comienza agregando tu primer registro de licencia, dominio o
                        servicio.</p>
                    <a href="{{ route('control.create') }}"
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span>Agregar primer registro</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="confirmDeleteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Confirmación de Eliminación</h2>
            <p id="modalMessage" class="mb-4"></p>
            <div class="flex justify-end">
                <button id="cancelButton" class="px-4 py-2 bg-gray-300 rounded-lg mr-2" onclick="toggleModal(false)">Cancelar</button>
                <button id="confirmButton" class="px-4 py-2 bg-red-500 text-white rounded-lg">Eliminar</button>
            </div>
        </div>
    </div>

    <script>
        let currentForm;

        function confirmDelete(event, nombre) {
            event.preventDefault();
            document.getElementById('modalMessage').innerText = `¿Está seguro de eliminar el registro "${nombre}"? Esta acción no se puede deshacer.`;
            currentForm = event.target.closest('form');
            toggleModal(true);
        }

        function toggleModal(show) {
            const modal = document.getElementById('confirmDeleteModal');
            modal.classList.toggle('hidden', !show);
            if (show) {
                document.getElementById('confirmButton').onclick = function() {
                    currentForm.submit();
                };
            }
        }
    </script>
</x-app-layout>
