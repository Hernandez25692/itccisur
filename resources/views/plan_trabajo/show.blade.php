<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">

        <x-flash class="!mb-0" />

        <div class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] rounded-2xl p-5 sm:p-8 text-white shadow-xl">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-white/10 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold">Plan de Trabajo TI {{ $plan->anio }}</h1>
                            <p class="text-gray-300 mt-1">Gestión estratégica de actividades tecnológicas</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 mt-6">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                            @if ($plan->estado == 'aprobado') bg-green-100 text-green-800
                            @elseif($plan->estado == 'enviado') bg-blue-100 text-blue-800
                            @elseif($plan->estado == 'rechazado') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if ($plan->estado == 'aprobado')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($plan->estado == 'enviado')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            @elseif($plan->estado == 'rechazado')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @endif
                            {{ ucfirst($plan->estado) }}
                        </div>

                        <div class="flex items-center gap-3 min-w-[220px]">
                            <div class="flex-1 h-2 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-[#C5A049]" style="width: {{ $resumen['avance'] }}%"></div>
                            </div>
                            <span class="text-sm font-bold">{{ $resumen['avance'] }}% de avance</span>
                        </div>

                        @if ($plan->fecha_aprobacion)
                            <div class="flex items-center gap-2 text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Revisado el {{ optional($plan->fecha_aprobacion)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    @role('admin_ti')
                        @if ($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                            <form action="{{ route('plan-trabajo.enviar', $plan) }}" method="POST">
                                @csrf
                                <button
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Enviar a Gerencia
                                </button>
                            </form>

                            @if ($plan->estado === 'borrador' || $plan->estado === 'rechazado')
                                <a href="{{ route('plan-trabajo.edit', $plan) }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-sm text-white border border-white/20 rounded-xl hover:bg-white/20 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar Plan
                                </a>
                            @endif
                        @endif
                    @endrole
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-gradient-to-r from-[#0C1C3C]/10 to-[#1A2A4F]/10 rounded-xl">
                    <svg class="w-6 h-6 text-[#0C1C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3">
            @foreach ([
                ['Actividades', $resumen['total'], 'text-[#0C1C3C]', null],
                ['Finalizadas', $resumen['finalizadas'], 'text-green-600', 'finalizado'],
                ['En progreso', $resumen['en_progreso'], 'text-blue-600', 'en_progreso'],
                ['Pendientes', $resumen['pendientes'], 'text-yellow-600', 'pendiente'],
                ['Atrasadas', $resumen['atrasadas'], 'text-red-600', 'atrasada'],
            ] as [$etiqueta, $valor, $color, $situacion])
                <a href="{{ route('plan-trabajo.show', array_filter(['plan' => $plan->id, 'situacion' => $situacion])) }}"
                    class="bg-white rounded-xl border p-4 shadow-sm hover:shadow transition {{ ($filtros['situacion'] ?? null) === $situacion && $situacion ? 'border-[#C5A049] ring-2 ring-[#C5A049]/30' : 'border-gray-200' }}">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide truncate">{{ $etiqueta }}</p>
                    <p class="text-2xl font-bold mt-1 {{ $color }}">{{ $valor }}</p>
                </a>
            @endforeach
            <a href="{{ route('bitacora.index', ['plan' => 'con']) }}"
                class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow transition">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide truncate" title="Registros de bitácora vinculados al plan">Bitácoras</p>
                <p class="text-2xl font-bold mt-1 text-[#B59038]">{{ $resumen['bitacoras'] }}</p>
            </a>
        </div>

        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Actividades del Plan</h2>
                    <p class="text-sm text-gray-600">
                        @if (count(array_filter($filtros)))
                            Mostrando {{ $actividades->count() }} de {{ $resumen['total'] }} actividades
                        @else
                            {{ $resumen['total'] }} actividades registradas
                        @endif
                    </p>
                </div>

                @role('admin_ti')
                    @if ($plan->esEditable())
                        <a href="{{ route('plan-actividad.create', $plan->id) }}"
                            class="inline-flex items-center justify-center gap-2 h-10 px-4 bg-[#0C1C3C] text-white text-sm font-semibold rounded-lg hover:bg-[#1A2A4F]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar actividad
                        </a>
                    @endif
                @endrole
            </div>

            @if ($resumen['total'] > 0)
                <x-filtros :limpiar="route('plan-trabajo.show', $plan)" :activos="count(array_filter($filtros))" :columnas="5">
                    <x-filtro label="Buscar">
                        <input type="text" name="buscar" value="{{ $filtros['buscar'] ?? '' }}" class="filtro-control"
                            placeholder="Actividad u objetivo">
                    </x-filtro>
                    <x-filtro label="Sección">
                        <select name="seccion" class="filtro-control">
                            <option value="">Todas</option>
                            @foreach ($secciones as $s)
                                <option value="{{ $s }}" @selected(($filtros['seccion'] ?? '') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </x-filtro>
                    <x-filtro label="Situación">
                        <select name="situacion" class="filtro-control">
                            <option value="">Cualquiera</option>
                            @foreach (['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'finalizado' => 'Finalizada', 'atrasada' => 'Atrasada'] as $valor => $texto)
                                <option value="{{ $valor }}" @selected(($filtros['situacion'] ?? '') === $valor)>{{ $texto }}</option>
                            @endforeach
                        </select>
                    </x-filtro>
                    <x-filtro label="Responsable">
                        <select name="responsable" class="filtro-control">
                            <option value="">Cualquiera</option>
                            @foreach ($responsables as $r)
                                <option value="{{ $r }}" @selected(($filtros['responsable'] ?? '') === $r)>{{ $r }}</option>
                            @endforeach
                        </select>
                    </x-filtro>
                    <x-filtro label="Mes previsto">
                        <select name="mes" class="filtro-control">
                            <option value="">Todos</option>
                            @foreach (\App\Models\PlanActividad::MESES as $n => $nombre)
                                <option value="{{ $n }}" @selected((int) ($filtros['mes'] ?? 0) === $n)>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </x-filtro>
                </x-filtros>
            @endif

            @if ($resumen['total'] == 0)
                <div class="rounded-2xl border border-gray-200 bg-white py-12 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No hay actividades registradas</h3>
                    <p class="text-gray-600">Comienza agregando la primera actividad al plan.</p>
                </div>
            @elseif ($actividades->isEmpty())
                <div class="rounded-2xl border border-gray-200 bg-white py-10 text-center text-gray-600">
                    Ninguna actividad coincide con los filtros.
                    <a href="{{ route('plan-trabajo.show', $plan) }}" class="text-[#B59038] font-medium">Ver todas</a>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="hidden xl:grid xl:grid-cols-[3rem_minmax(0,1fr)_11rem_10rem_8.5rem] gap-4 border-b border-gray-200 bg-gray-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <span>#</span>
                        <span>Actividad</span>
                        <span>Responsable / Fecha</span>
                        <span>Avance</span>
                        <span class="text-right">Acciones</span>
                    </div>

                    @foreach ($actividades->groupBy('seccion') as $seccion => $grupo)
                        <div class="border-b border-gray-200 bg-[#0C1C3C]/5 px-5 py-2.5 text-sm font-bold uppercase tracking-wide text-[#0C1C3C] break-words">
                            {{ $numeroSeccion[$seccion] ?? '' }} — {{ $seccion }}
                        </div>

                        <ul class="divide-y divide-gray-100">
                            @foreach ($grupo as $act)
                                @php
                                    $progreso = $act->progreso;
                                    [$estadoTexto, $estadoClase] = match (true) {
                                        $progreso >= 100 => ['Completada', 'bg-green-100 text-green-800'],
                                        $progreso > 0 => ['En progreso', 'bg-blue-100 text-blue-800'],
                                        default => ['Pendiente', 'bg-yellow-100 text-yellow-800'],
                                    };
                                @endphp
                                <li class="grid gap-3 px-4 py-4 sm:px-5 xl:grid-cols-[3rem_minmax(0,1fr)_11rem_10rem_8.5rem] xl:items-start xl:gap-4 {{ $act->atrasada ? 'bg-red-50/40' : '' }}">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-[#0C1C3C]/10 text-[#0C1C3C]">
                                            {{ $numeracion[$act->id] ?? $act->codigo }}
                                        </span>
                                    </div>

                                    <div class="min-w-0">
                                        <a href="{{ route('plan-actividad.show', $act->id) }}"
                                            class="block font-semibold text-gray-900 hover:text-[#B59038] break-words">{{ $act->actividad }}</a>
                                        <p class="mt-0.5 text-sm text-gray-500 line-clamp-2 break-words">{{ $act->objetivo }}</p>
                                        @if ($act->bitacoras_count)
                                            <a href="{{ route('bitacora.index', ['plan_actividad_id' => $act->id]) }}"
                                                class="mt-1.5 inline-flex items-center gap-1 text-xs font-medium text-[#B59038] hover:underline">
                                                📋 {{ $act->bitacoras_count }} registro{{ $act->bitacoras_count > 1 ? 's' : '' }} en bitácora
                                            </a>
                                        @endif
                                    </div>

                                    <div class="min-w-0 text-sm">
                                        <p class="font-medium text-gray-900 truncate" title="{{ $act->responsable }}">{{ $act->responsable }}</p>
                                        <p class="text-gray-500 truncate">
                                            {{ collect([$act->mes_previsto, $act->fecha_ejecucion])->filter()->implode(' · ') ?: 'Sin fecha' }}
                                        </p>
                                    </div>

                                    <div class="space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $estadoClase }}">{{ $estadoTexto }}</span>
                                            @if ($act->atrasada)
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Atrasada</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 flex-1 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $progreso >= 100 ? 'bg-green-500' : ($progreso > 0 ? 'bg-yellow-500' : 'bg-gray-400') }}"
                                                    style="width: {{ $progreso }}%"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-700 w-9 text-right">{{ $progreso }}%</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 xl:flex-col xl:items-end">
                                        <a href="{{ route('plan-actividad.show', $act->id) }}"
                                            class="inline-flex h-9 items-center px-3 rounded-lg bg-[#0C1C3C] text-white text-sm font-medium hover:bg-[#1A2A4F] whitespace-nowrap">
                                            Ver detalle
                                        </a>
                                        @role('admin_ti')
                                            @if ($plan->estado === 'aprobado' && $progreso < 100)
                                                <a href="{{ route('bitacora.create', ['plan_actividad_id' => $act->id]) }}"
                                                    class="inline-flex h-9 items-center px-3 rounded-lg border border-[#C5A049] text-[#8a6d22] text-sm font-medium hover:bg-[#C5A049]/10 whitespace-nowrap">
                                                    + Bitácora
                                                </a>
                                            @endif
                                        @endrole
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            @endif
        </div>

        @role('gerencia')
            @if ($plan->estado === 'enviado')
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                            <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Revisión y Aprobación de Gerencia</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                            <form action="{{ route('plan-trabajo.aprobar', $plan) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <label class="block font-semibold text-green-800 mb-3">Comentario de Aprobación
                                        (opcional)</label>
                                    <textarea name="comentario_gerencia" rows="4"
                                        class="w-full border border-green-300 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-colors"
                                        placeholder="Agrega comentarios o sugerencias...">{{ old('comentario_gerencia') }}</textarea>
                                </div>
                                <button
                                    class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Aprobar Plan
                                </button>
                            </form>
                        </div>

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
                                <button
                                    class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rechazar Plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endrole

        @if ($plan->comentario_gerencia && $plan->estado !== 'enviado')
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-gradient-to-r from-[#1A2A4F]/10 to-[#0C1C3C]/10 rounded-xl">
                        <svg class="w-6 h-6 text-[#1A2A4F]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Comentario de Gerencia</h2>
                </div>

                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6">
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $plan->comentario_gerencia }}</p>
                </div>
            </div>
        @endif

        @if ($plan->revisiones->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-gradient-to-r from-[#C5A049]/10 to-[#D8B96E]/10 rounded-xl">
                        <svg class="w-6 h-6 text-[#C5A049]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Historial de Revisiones</h2>
                </div>

                <div class="space-y-4">
                    @foreach ($plan->revisiones as $rev)
                        <div class="border border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span
                                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold
                                            @if ($rev->accion == 'aprobado') bg-green-100 text-green-800
                                            @elseif($rev->accion == 'rechazado') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($rev->accion) }}
                                        </span>
                                        <span
                                            class="text-sm text-gray-600">{{ $rev->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="font-semibold text-gray-900">
                                        Por {{ $rev->usuario->name ?? 'Usuario eliminado' }}
                                    </p>
                                    @if ($rev->comentario)
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
