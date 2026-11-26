<x-app-layout>
    @php
        $filtrosActivos = collect(request()->only(['buscar', 'estado', 'mes', 'anio', 'semana', 'area', 'red', 'tipo', 'creado_por']))
            ->filter(fn ($v) => filled($v))
            ->count();

        $estilos = [
            'publicado' => ['texto' => 'Publicado', 'badge' => 'bg-green-100 text-green-800 border-green-200', 'fondo' => 'bg-green-50/40'],
            'pendiente' => ['texto' => 'Pendiente', 'badge' => 'bg-orange-100 text-orange-800 border-orange-200', 'fondo' => 'bg-orange-50/40'],
            'reprogramado' => ['texto' => 'Reprogramado', 'badge' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'fondo' => 'bg-yellow-50/40'],
            'cancelado' => ['texto' => 'Cancelado', 'badge' => 'bg-red-100 text-red-800 border-red-200', 'fondo' => 'bg-red-50/40'],
        ];
        $esAdmin = auth()->user()->hasRole('admin_ti');
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Encabezado --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="shrink-0 p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Calendario Editorial</h1>
                    <p class="text-sm text-gray-600">Publicaciones para las redes sociales institucionales</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @role('admin_ti|calendario')
                    <a href="{{ route('calendario-editorial.create') }}"
                        class="inline-flex items-center gap-2 h-10 px-4 rounded-lg bg-blue-600 text-white text-sm font-semibold shadow-sm hover:bg-blue-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva publicación
                    </a>
                @endrole
                <a href="{{ route('calendario-editorial.calendar', request()->only(['estado', 'area', 'red', 'tipo', 'buscar'])) }}"
                    class="inline-flex items-center gap-2 h-10 px-4 rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Vista calendario
                </a>
                <a href="{{ route('calendario-editorial.dashboard', array_filter(['mes' => request('mes'), 'anio' => request('anio') ?: (request('mes') ? now()->year : null), 'area' => request('area'), 'red' => request('red'), 'tipo' => request('tipo')])) }}"
                    class="inline-flex items-center gap-2 h-10 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50">
                    📊 Dashboard
                </a>
            </div>
        </div>

        <x-flash class="!mb-0" />

        {{-- Resumen por estado (clic para filtrar) --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-5">
            <a href="{{ request()->fullUrlWithQuery(['estado' => null, 'page' => null]) }}"
                class="rounded-xl border bg-white p-4 shadow-sm hover:shadow transition {{ request('estado') ? 'border-gray-200' : 'border-blue-400 ring-2 ring-blue-100' }}">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $porEstado->sum() }}</p>
            </a>
            @foreach ($estilos as $estado => $e)
                <a href="{{ request()->fullUrlWithQuery(['estado' => request('estado') === $estado ? null : $estado, 'page' => null]) }}"
                    class="rounded-xl border bg-white p-4 shadow-sm hover:shadow transition {{ request('estado') === $estado ? 'border-blue-400 ring-2 ring-blue-100' : 'border-gray-200' }}">
                    <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ \App\Models\CalendarioEditorial::COLORES[$estado] }}"></span>
                        <span class="truncate">{{ $e['texto'] }}</span>
                    </p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $porEstado[$estado] ?? 0 }}</p>
                </a>
            @endforeach
        </div>

        @if ($vencidas > 0)
            <div class="flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <span aria-hidden="true">⚠️</span>
                <span><strong>{{ $vencidas }}</strong> {{ $vencidas > 1 ? 'publicaciones pendientes' : 'publicación pendiente' }}
                    con fecha pasada, sin publicar.</span>
            </div>
        @endif

        {{-- Filtros --}}
        <x-filtros :limpiar="route('calendario-editorial.index')" :activos="$filtrosActivos"
            :resumen="$registros->total() . ' publicaciones'">
            <x-filtro label="Buscar" :ancho="2">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="filtro-control"
                    placeholder="Tema, encabezado o etiqueta">
            </x-filtro>
            <x-filtro label="Estado">
                <select name="estado" class="filtro-control">
                    <option value="">Todos</option>
                    @foreach ($estilos as $estado => $e)
                        <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ $e['texto'] }}</option>
                    @endforeach
                </select>
            </x-filtro>
            <x-filtro label="Orden">
                <select name="orden" class="filtro-control">
                    <option value="desc">Más recientes primero</option>
                    <option value="asc" @selected(request('orden') === 'asc')>Más antiguas primero</option>
                </select>
            </x-filtro>
            <x-filtro label="Mes">
                <select name="mes" class="filtro-control">
                    <option value="">Todos</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" @selected(request('mes') == $m)>
                            {{ ucfirst(\Carbon\Carbon::create(null, $m, 1)->translatedFormat('F')) }}
                        </option>
                    @endforeach
                </select>
            </x-filtro>
            <x-filtro label="Año">
                <select name="anio" class="filtro-control">
                    <option value="">Todos</option>
                    @foreach ($anios as $a)
                        <option value="{{ $a }}" @selected(request('anio') == $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </x-filtro>
            <x-filtro label="Semana del mes">
                <input type="number" name="semana" min="1" max="6" value="{{ request('semana') }}" class="filtro-control"
                    placeholder="1 a 6">
            </x-filtro>
            <x-filtro label="Autor">
                <select name="creado_por" class="filtro-control">
                    <option value="">Cualquiera</option>
                    @foreach ($creadores as $id => $nombre)
                        <option value="{{ $id }}" @selected((string) request('creado_por') === (string) $id)>{{ $nombre }}</option>
                    @endforeach
                </select>
            </x-filtro>
            <x-filtro label="Área" :ancho="2">
                <select name="area" class="filtro-control">
                    <option value="">Todas</option>
                    @foreach (\App\Models\CalendarioEditorial::AREAS as $area)
                        <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                    @endforeach
                </select>
            </x-filtro>
            <x-filtro label="Red social">
                <select name="red" class="filtro-control">
                    <option value="">Todas</option>
                    @foreach (\App\Models\CalendarioEditorial::REDES as $red)
                        <option value="{{ $red }}" @selected(request('red') === $red)>{{ ucfirst(strtolower($red)) }}</option>
                    @endforeach
                </select>
            </x-filtro>
            <x-filtro label="Tipo de contenido">
                <select name="tipo" class="filtro-control">
                    <option value="">Todos</option>
                    @foreach (\App\Models\CalendarioEditorial::TIPOS_CONTENIDO as $tipo)
                        <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ ucfirst(strtolower($tipo)) }}</option>
                    @endforeach
                </select>
            </x-filtro>
        </x-filtros>

        {{-- Tabla compacta: una línea por publicación. El detalle completo está en "Ver". --}}
        @php
            $redesCorto = [
                'FACEBOOK' => ['FB', '#1877F2'], 'INSTAGRAM' => ['IG', '#E1306C'], 'X' => ['X', '#111827'],
                'LINKEDIN' => ['IN', '#0A66C2'], 'YOUTUBE' => ['YT', '#FF0000'], 'WHATSAPP' => ['WA', '#25D366'],
                'TIKTOK' => ['TT', '#000000'], 'OTRA' => ['+', '#6B7280'],
            ];
        @endphp
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="hidden whitespace-nowrap py-3 pl-5 pr-3 sm:table-cell">Fecha</th>
                            <th class="w-full py-3 pl-4 pr-3 sm:pl-3">Tema</th>
                            <th class="hidden whitespace-nowrap px-3 py-3 xl:table-cell">Redes</th>
                            <th class="hidden whitespace-nowrap px-3 py-3 sm:table-cell">Estado</th>
                            <th class="whitespace-nowrap py-3 pl-3 pr-5 text-right"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($registros as $item)
                            @php $e = $estilos[$item->estado] ?? ['texto' => ucfirst($item->estado), 'badge' => 'bg-gray-100 text-gray-700 border-gray-200']; @endphp
                            <tr class="hover:bg-gray-50">
                                {{-- Fecha: el color de la izquierda indica el estado --}}
                                <td class="hidden whitespace-nowrap py-3 pl-5 pr-3 align-middle sm:table-cell" style="box-shadow: inset 4px 0 0 {{ $item->color }};">
                                    <div class="font-semibold text-gray-900">{{ $item->fecha_publicacion->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ mb_substr($item->dia, 0, 3) }}{{ $item->hora ? ' ' . substr($item->hora, 0, 5) : '' }} · S{{ $item->semana }}
                                    </div>
                                </td>

                                <td class="max-w-0 py-3 pl-4 pr-3 align-middle sm:pl-3">
                                    <a href="{{ route('calendario-editorial.show', $item) }}" title="{{ $item->tema }}"
                                        class="break-words font-medium text-gray-900 line-clamp-2 hover:text-blue-700 xl:line-clamp-1">
                                        {{ $item->tema }}
                                    </a>
                                    {{-- En teléfonos la fecha y el estado van aquí, porque esas columnas se ocultan --}}
                                    <div class="mt-0.5 text-xs text-gray-500 sm:hidden">
                                        {{ $item->fecha_publicacion->format('d/m/Y') }} · {{ mb_substr($item->dia, 0, 3) }}{{ $item->hora ? ' ' . substr($item->hora, 0, 5) : '' }}
                                        · <span class="font-semibold" style="color: {{ $item->color }}">{{ $e['texto'] }}</span>
                                    </div>
                                    @if ($item->area)
                                        <div class="mt-0.5 truncate text-xs text-gray-500">{{ $item->area }}</div>
                                    @endif
                                </td>

                                <td class="hidden whitespace-nowrap px-3 py-3 align-middle xl:table-cell">
                                    <div class="flex gap-1">
                                        @php $redes = collect($item->publicar_en ?? []); @endphp
                                        @forelse ($redes->take(4) as $red)
                                            @php [$sigla, $color] = $redesCorto[$red] ?? [mb_substr($red, 0, 2), '#6B7280']; @endphp
                                            <span class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded px-1 text-[10px] font-bold text-white"
                                                style="background: {{ $color }}" title="{{ ucfirst(strtolower($red)) }}">{{ $sigla }}</span>
                                        @empty
                                            <span class="text-gray-400">—</span>
                                        @endforelse
                                        @if ($redes->count() > 4)
                                            <span class="inline-flex h-6 items-center justify-center rounded bg-gray-100 px-1.5 text-[10px] font-bold text-gray-600"
                                                title="{{ $redes->slice(4)->map(fn ($r) => ucfirst(strtolower($r)))->implode(', ') }}">+{{ $redes->count() - 4 }}</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="hidden whitespace-nowrap px-3 py-3 align-middle sm:table-cell">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $e['badge'] }}">
                                        {{ $e['texto'] }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap py-3 pl-3 pr-5 align-middle">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('calendario-editorial.show', $item) }}" title="Ver detalle" aria-label="Ver detalle"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if ($item->puedeEditar(auth()->user()))
                                            <a href="{{ route('calendario-editorial.edit', $item) }}" title="Editar" aria-label="Editar"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                        @if ($esAdmin && $item->estado !== 'publicado')
                                            <form id="publicar-form-{{ $item->id }}" method="POST" action="{{ route('calendario-editorial.publicar', $item) }}">
                                                @csrf
                                                <button type="button" onclick="confirmarPublicacion({{ $item->id }})" title="Marcar como publicada" aria-label="Marcar como publicada"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-600 text-white hover:bg-green-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <p class="text-base font-semibold text-gray-700">
                                        {{ $filtrosActivos ? 'Ninguna publicación coincide con los filtros' : 'No hay publicaciones programadas' }}
                                    </p>
                                    @if ($filtrosActivos)
                                        <a href="{{ route('calendario-editorial.index') }}" class="mt-2 inline-block text-sm font-medium text-blue-600">Quitar filtros</a>
                                    @else
                                        @role('admin_ti|calendario')
                                            <a href="{{ route('calendario-editorial.create') }}" class="mt-2 inline-block text-sm font-medium text-blue-600">Crear la primera publicación</a>
                                        @endrole
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($registros->hasPages())
                <div class="border-t border-gray-200 px-4 py-4 sm:px-5">
                    {{ $registros->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarPublicacion(id) {
            Swal.fire({
                title: '¿Marcar como publicada?',
                text: 'Se registrará la fecha y quién la publicó.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, publicar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('publicar-form-' + id)?.submit();
                }
            });
        }
    </script>
</x-app-layout>
