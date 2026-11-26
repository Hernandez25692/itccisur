<x-app-layout>
    @include('calendario_editorial.index-styles')

    @php
        $estados = [
            'publicado' => 'Publicado',
            'pendiente' => 'Pendiente',
            'reprogramado' => 'Reprogramado',
            'cancelado' => 'Cancelado',
        ];
        $filtrosAvanzados = collect(request()->only(['mes', 'anio', 'semana', 'area', 'red', 'tipo', 'creado_por']))
            ->filter(fn ($valor) => filled($valor))
            ->count() + (int) (request('orden') === 'asc');
        $filtrosActivos = $filtrosAvanzados || filled(request('buscar')) || filled(request('estado'));
        $totalPorEstado = $porEstado->sum();
        $usuario = auth()->user();
        $esAdmin = $usuario->hasRole('admin_ti');
        $puedeCrear = $usuario->hasAnyRole(['admin_ti', 'calendario']);
        $redesCortas = [
            'FACEBOOK' => ['FB', '#1c57a4', 'Facebook'],
            'INSTAGRAM' => ['IG', '#9d2c61', 'Instagram'],
            'X' => ['X', '#303640', 'X'],
            'LINKEDIN' => ['IN', '#135d88', 'LinkedIn'],
            'YOUTUBE' => ['YT', '#a82429', 'YouTube'],
            'WHATSAPP' => ['WA', '#226643', 'WhatsApp'],
            'TIKTOK' => ['TT', '#303640', 'TikTok'],
            'OTRA' => ['+', '#5e6374', 'Otra'],
        ];
        $urlCalendario = route('calendario-editorial.calendar', request()->only(['estado', 'area', 'red', 'tipo', 'buscar']));
        $urlIndicadores = route('calendario-editorial.dashboard', array_filter([
            'mes' => request('mes'),
            'anio' => request('anio') ?: (request('mes') ? now()->year : null),
            'area' => request('area'),
            'red' => request('red'),
            'tipo' => request('tipo'),
        ]));
        $mesAnterior = null;
    @endphp

    <div class="editorial-desk">
        <header class="editorial-masthead">
            <div class="editorial-cover-grid" aria-hidden="true"></div>
            <div class="editorial-masthead-main">
                <div>
                    <p class="editorial-imprint">CCISUR <span aria-hidden="true">/</span> Comunicaciones</p>
                    <h1>Calendario <em>editorial</em></h1>
                    <p class="editorial-deck">Plan de publicaciones para las redes institucionales.</p>
                </div>
                @if ($puedeCrear)
                    <a href="{{ route('calendario-editorial.create') }}" class="editorial-create">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                        Nueva publicación
                    </a>
                @endif
            </div>

            <nav class="editorial-views" aria-label="Vistas de calendario editorial">
                <a href="{{ request()->fullUrl() }}" aria-current="page">
                    <i class="fa-solid fa-list-ul" aria-hidden="true"></i> Agenda
                </a>
                <a href="{{ $urlCalendario }}">
                    <i class="fa-regular fa-calendar-days" aria-hidden="true"></i> Calendario
                </a>
                <a href="{{ $urlIndicadores }}">
                    <i class="fa-solid fa-chart-simple" aria-hidden="true"></i> Indicadores
                </a>
            </nav>
        </header>

        <x-flash class="editorial-flash !mb-0" />

        <section class="editorial-register" aria-labelledby="editorial-register-title">
            <nav class="editorial-status-rail" aria-label="Filtrar por estado">
                <a href="{{ request()->fullUrlWithQuery(['estado' => null, 'page' => null]) }}"
                    class="editorial-status-tab editorial-status-total" @if (!filled(request('estado'))) aria-current="true" @endif>
                    <span class="editorial-status-label">Todas</span>
                    <strong>{{ $totalPorEstado }}</strong>
                </a>
                @foreach ($estados as $estado => $nombre)
                    <a href="{{ request()->fullUrlWithQuery(['estado' => request('estado') === $estado ? null : $estado, 'page' => null]) }}"
                        class="editorial-status-tab" data-state="{{ $estado }}" style="--state: {{ \App\Models\CalendarioEditorial::COLORES[$estado] }}"
                        @if (request('estado') === $estado) aria-current="true" @endif>
                        <span class="editorial-status-label"><span class="editorial-dot" aria-hidden="true"></span>{{ $nombre }}</span>
                        <strong>{{ $porEstado[$estado] ?? 0 }}</strong>
                    </a>
                @endforeach
            </nav>

            @if ($vencidas > 0)
                <p class="editorial-overdue">
                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                    <span><strong>{{ $vencidas }} {{ $vencidas === 1 ? 'publicación pendiente' : 'publicaciones pendientes' }}</strong> con fecha vencida.</span>
                </p>
            @endif

            <div class="editorial-register-heading">
                <h2 id="editorial-register-title">Plan de publicaciones</h2>
                <span>{{ $registros->total() }} {{ $registros->total() === 1 ? 'publicación' : 'publicaciones' }}</span>
            </div>

            <form method="GET" action="{{ route('calendario-editorial.index') }}" class="editorial-filters" role="search" aria-label="Buscar publicaciones">
                <input type="hidden" name="estado" value="{{ request('estado') }}">
                <div class="editorial-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <label for="editorial-search" class="sr-only">Tema, encabezado o etiqueta</label>
                    <input id="editorial-search" type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Tema, encabezado o etiqueta">
                    <button type="submit">Buscar</button>
                </div>

                <details class="editorial-filter-panel" @if ($filtrosAvanzados) open @endif>
                    <summary>
                        <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                        <span>Filtros</span>
                        @if ($filtrosAvanzados)
                            <span class="editorial-filter-count" aria-label="{{ $filtrosAvanzados }} filtros aplicados">{{ $filtrosAvanzados }}</span>
                        @endif
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </summary>
                    <div class="editorial-filter-fields">
                        <div class="editorial-filter-grid">
                            <x-filtro label="Mes">
                                <select name="mes" class="filtro-control">
                                    <option value="">Todos</option>
                                    @foreach (range(1, 12) as $mes)
                                        <option value="{{ $mes }}" @selected(request('mes') == $mes)>
                                            {{ ucfirst(\Carbon\Carbon::create(null, $mes, 1)->translatedFormat('F')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-filtro>
                            <x-filtro label="Año">
                                <select name="anio" class="filtro-control">
                                    <option value="">Todos</option>
                                    @foreach ($anios as $anio)
                                        <option value="{{ $anio }}" @selected(request('anio') == $anio)>{{ $anio }}</option>
                                    @endforeach
                                </select>
                            </x-filtro>
                            <x-filtro label="Semana del mes">
                                <input type="number" name="semana" min="1" max="6" value="{{ request('semana') }}" class="filtro-control" placeholder="1 a 6">
                            </x-filtro>
                            <x-filtro label="Orden">
                                <select name="orden" class="filtro-control">
                                    <option value="desc">Más recientes primero</option>
                                    <option value="asc" @selected(request('orden') === 'asc')>Más antiguas primero</option>
                                </select>
                            </x-filtro>
                            <x-filtro label="Área">
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
                                        <option value="{{ $red }}" @selected(request('red') === $red)>{{ $redesCortas[$red][2] }}</option>
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
                            <x-filtro label="Autor">
                                <select name="creado_por" class="filtro-control">
                                    <option value="">Cualquiera</option>
                                    @foreach ($creadores as $id => $nombre)
                                        <option value="{{ $id }}" @selected((string) request('creado_por') === (string) $id)>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </x-filtro>
                        </div>
                        <div class="editorial-filter-actions">
                            <button type="submit" class="editorial-apply">Aplicar filtros</button>
                        </div>
                    </div>
                </details>

                @if ($filtrosActivos)
                    <a href="{{ route('calendario-editorial.index') }}" class="editorial-clear">Limpiar filtros</a>
                @endif
            </form>

            @if ($registros->isNotEmpty())
                <table class="editorial-table" role="table" aria-labelledby="editorial-register-title">
                    <thead role="rowgroup">
                        <tr role="row">
                            <th role="columnheader" scope="col">Fecha</th>
                            <th role="columnheader" scope="col">Tema</th>
                            <th role="columnheader" scope="col">Redes</th>
                            <th role="columnheader" scope="col">Estado</th>
                            <th role="columnheader" scope="col"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody role="rowgroup">
                        @foreach ($registros as $item)
                            @php
                                $fecha = $item->fecha_publicacion;
                                $mesActual = $fecha->format('Y-m');
                                $redes = collect($item->publicar_en ?? []);
                                $tipos = collect($item->contenido ?? [])->map(fn ($tipo) => ucfirst(mb_strtolower($tipo)));
                                $detalle = route('calendario-editorial.show', $item);
                            @endphp

                            @if ($mesActual !== $mesAnterior)
                                <tr class="editorial-month" role="row">
                                    <th colspan="5" role="cell">
                                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                                        {{ $fecha->translatedFormat('F') }} <span>{{ $fecha->year }}</span>
                                    </th>
                                </tr>
                                @php $mesAnterior = $mesActual; @endphp
                            @endif

                            <tr class="editorial-entry" role="row" data-state="{{ $item->estado }}" style="--state: {{ $item->color }}">
                                <td class="editorial-date" role="cell">
                                    <time datetime="{{ $fecha->toDateString() }}" title="{{ $fecha->format('d/m/Y') }}">
                                        <strong>{{ $fecha->format('d') }}</strong>
                                        <span>{{ $fecha->translatedFormat('D') }}</span>
                                    </time>
                                    <span class="editorial-date-note">
                                        {{ $item->hora ? substr($item->hora, 0, 5) . ' · ' : '' }}<abbr title="Semana {{ $item->semana }}">S{{ $item->semana }}</abbr>
                                    </span>
                                </td>
                                <td class="editorial-topic" role="cell">
                                    <a href="{{ $detalle }}">{{ $item->tema }}</a>
                                    @if ($item->area || $tipos->isNotEmpty())
                                        <div class="editorial-topic-meta">
                                            @if ($item->area)
                                                <span>{{ $item->area }}</span>
                                            @endif
                                            @if ($tipos->isNotEmpty())
                                                <span class="editorial-formats">{{ $tipos->implode(' · ') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="editorial-channels" role="cell">
                                    <div class="editorial-channel-list">
                                        @forelse ($redes->take(4) as $red)
                                            @php [$sigla, $color, $nombreRed] = $redesCortas[$red] ?? [mb_substr($red, 0, 2), '#5e6374', $red]; @endphp
                                            <abbr class="editorial-channel" style="--channel: {{ $color }}" title="{{ $nombreRed }}">{{ $sigla }}</abbr>
                                        @empty
                                            <span class="editorial-no-channels" aria-label="Sin redes asignadas">—</span>
                                        @endforelse
                                        @if ($redes->count() > 4)
                                            <abbr class="editorial-channel" title="{{ $redes->slice(4)->map(fn ($red) => $redesCortas[$red][2] ?? $red)->implode(', ') }}">+{{ $redes->count() - 4 }}</abbr>
                                        @endif
                                    </div>
                                </td>
                                <td class="editorial-entry-state" role="cell">
                                    <span class="editorial-state-label">
                                        <span class="editorial-dot" aria-hidden="true"></span>
                                        {{ $estados[$item->estado] ?? ucfirst($item->estado) }}
                                    </span>
                                </td>
                                <td class="editorial-entry-actions" role="cell">
                                    <div>
                                        <a href="{{ $detalle }}" class="editorial-icon-action" title="Ver detalle" aria-label="Ver detalle">
                                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                        </a>
                                        @if ($item->puedeEditar($usuario))
                                            <a href="{{ route('calendario-editorial.edit', $item) }}" class="editorial-icon-action" title="Editar" aria-label="Editar">
                                                <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if ($esAdmin && !$item->esPublicado())
                                            <form method="POST" action="{{ route('calendario-editorial.publicar', $item) }}" onsubmit="confirmarPublicacion(event)">
                                                @csrf
                                                <button type="submit" class="editorial-icon-action editorial-publish" title="Marcar como publicada" aria-label="Marcar como publicada">
                                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="editorial-empty">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <p>{{ $filtrosActivos ? 'Ninguna publicación coincide con los filtros' : 'No hay publicaciones programadas' }}</p>
                    @if ($filtrosActivos)
                        <a href="{{ route('calendario-editorial.index') }}">Quitar filtros</a>
                    @elseif ($puedeCrear)
                        <a href="{{ route('calendario-editorial.create') }}">Crear la primera publicación</a>
                    @endif
                </div>
            @endif

            @if ($registros->hasPages())
                <div class="editorial-pagination">
                    {{ $registros->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>

    @if ($esAdmin)
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmarPublicacion(event) {
                const form = event.currentTarget;
                const titulo = '¿Marcar como publicada?';
                const mensaje = 'Se registrará la fecha y quién la publicó.';

                if (typeof Swal === 'undefined') {
                    if (!window.confirm(titulo + '\n' + mensaje)) event.preventDefault();
                    return;
                }

                event.preventDefault();
                Swal.fire({
                    title: titulo,
                    text: mensaje,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, publicar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#173551',
                    reverseButtons: true,
                }).then(({ isConfirmed }) => {
                    if (isConfirmed) form.submit();
                });
            }
        </script>
    @endif
</x-app-layout>
