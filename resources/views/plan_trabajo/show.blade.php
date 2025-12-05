<x-app-layout>

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        {{-- Encabezado del Plan --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Plan de Trabajo TI {{ $plan->anio }}</h1>
                <p class="text-gray-600">
                    Estado:
                    <span
                        class="font-semibold
                        @if ($plan->estado == 'borrador') text-gray-600
                        @elseif($plan->estado == 'enviado') text-blue-600
                        @elseif($plan->estado == 'aprobado') text-green-600
                        @elseif($plan->estado == 'rechazado') text-red-600 @endif">
                        {{ ucfirst($plan->estado) }}
                    </span>
                </p>

                {{-- FECHA DE REVISIÓN (APROBADO o RECHAZADO) --}}
                @if ($plan->fecha_aprobacion)
                    <p class="text-gray-500 text-sm">
                        Revisado el {{ optional($plan->fecha_aprobacion)->format('d/m/Y') }}
                    </p>
                @endif
            </div>

            {{-- BOTÓN TI → Enviar a Gerencia --}}
            @role('admin_ti')
                @if ($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                    <form action="{{ route('plan-trabajo.enviar', $plan) }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Enviar a Gerencia
                        </button>
                    </form>
                @endif

                {{-- TI puede editar si el plan está en borrador o rechazado --}}
                @if ($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                    <a href="{{ route('plan-trabajo.edit', $plan) }}"
                        class="ml-3 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        Editar Plan
                    </a>
                @endif
            @endrole
        </div>

        {{-- DESCRIPCIÓN GENERAL --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-3">Descripción General</h2>
            <p class="text-gray-700 leading-relaxed">
                {{ $plan->descripcion_general ?: 'Sin descripción general registrada.' }}
            </p>
        </div>

        {{-- TABLA DE ACTIVIDADES --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Actividades del Plan</h2>

            @if ($plan->actividades->count() == 0)
                <p class="text-gray-500">Aún no hay actividades registradas.</p>
            @else
                <table class="w-full border-collapse text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">ID</th>
                            <th class="p-2 border">Actividad</th>
                            <th class="p-2 border">Objetivo Específico</th>
                            <th class="p-2 border">Frecuencia</th>
                            <th class="p-2 border">Responsable</th>
                            <th class="p-2 border">Mes previsto</th>
                            <th class="p-2 border">Fecha a ejecutar</th>
                            <th class="p-2 border">Métrica de Éxito</th>
                            <th class="p-2 border">Estado</th>
                            <th class="p-2 border">Avance</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plan->actividades as $act)
                            <tr>
                                <td class="border p-2">{{ $act->codigo }}</td>
                                <td class="border p-2">{{ $act->actividad }}</td>
                                <td class="border p-2">{{ $act->objetivo }}</td>
                                <td class="border p-2">{{ $act->frecuencia }}</td>
                                <td class="border p-2">{{ $act->responsable }}</td>
                                <td class="border p-2">{{ $act->mes_previsto }}</td>
                                <td class="border p-2">{{ $act->fecha_ejecucion }}</td>
                                <td class="border p-2">{{ $act->metrica_exito }}</td>
                                <td class="border p-2 capitalize">
                                    @if ($act->progreso == 100)
                                        <span class="text-green-600 font-semibold">Completado</span>
                                    @else
                                        {{ ucfirst($act->estado) }}
                                    @endif
                                </td>

                                <td class="border p-2">

                                    <div class="w-full bg-gray-200 rounded h-4 overflow-hidden">
                                        <div class="h-4 rounded
            @if ($act->progreso == 100) bg-green-500
            @elseif($act->progreso > 0)
                bg-yellow-500
            @else
                bg-gray-400 @endif"
                                            style="width: {{ $act->progreso }}%">
                                        </div>
                                    </div>

                                    <span class="text-sm text-gray-700">{{ $act->progreso }}%</span>

                                </td>

                                <td class="border p-2 text-blue-600">
                                    <a href="{{ route('plan-actividad.show', $act->id) }}">
                                        Ver / Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- AGREGAR ACTIVIDAD (TI) --}}
            @role('admin_ti')
                @if ($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                    <div class="mt-4">
                        <a href="{{ route('plan-actividad.create', $plan->id) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Agregar Actividad
                        </a>
                    </div>
                @endif
            @endrole
        </div>

        {{-- REVISIÓN DE GERENCIA --}}
        @role('gerencia')
            @if ($plan->estado === 'enviado')
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Revisión y Aprobación de Gerencia</h2>

                    {{-- Aprobar --}}
                    <form action="{{ route('plan-trabajo.aprobar', $plan) }}" method="POST" class="mb-4">
                        @csrf
                        <label class="block font-semibold mb-1">Comentario de Gerencia (opcional al aprobar)</label>
                        <textarea name="comentario_gerencia" rows="3" class="w-full border rounded px-3 py-2 mb-3">{{ old('comentario_gerencia') }}</textarea>

                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Aprobar Plan
                        </button>
                    </form>

                    {{-- Rechazar --}}
                    <form action="{{ route('plan-trabajo.rechazar', $plan) }}" method="POST">
                        @csrf
                        <label class="block font-semibold mb-1">Comentario de Rechazo (obligatorio)</label>
                        <textarea name="comentario_gerencia" rows="3" required class="w-full border rounded px-3 py-2 mb-3"></textarea>

                        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Rechazar Plan
                        </button>
                    </form>
                </div>
            @endif
        @endrole

        {{-- COMENTARIO DE GERENCIA (si ya revisó) --}}
        @if ($plan->comentario_gerencia && $plan->estado !== 'enviado')
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-3">Comentario de Gerencia</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $plan->comentario_gerencia }}</p>
            </div>
        @endif
        @if ($plan->revisiones->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-3">Historial de Revisiones</h2>

                <ul class="space-y-2">
                    @foreach ($plan->revisiones as $rev)
                        <li class="border rounded p-3">
                            <p class="font-semibold">
                                {{ ucfirst($rev->accion) }} por {{ $rev->usuario->name ?? 'Usuario eliminado' }}
                                el {{ $rev->created_at->format('d/m/Y H:i') }}
                            </p>
                            @if ($rev->comentario)
                                <p class="text-gray-700 mt-1">
                                    Comentario: {{ $rev->comentario }}
                                </p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

</x-app-layout>
