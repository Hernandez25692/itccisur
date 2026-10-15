<x-app-layout>

    @include('rrhh.partials.ui')

    <style>
        .rh-roster {
            min-width: 980px;
        }

        .rh-roster .c-empleado {
            min-width: 14rem;
        }

        .rh-roster .c-vac {
            min-width: 12rem;
        }

        .rh-roster .c-acc {
            width: 1%;
            padding-top: 0.6rem;
        }

        .rh-roster .c-ingreso {
            white-space: nowrap;
        }

        .rh-roster .c-cargo {
            min-width: 9rem;
            max-width: 13rem;
        }

        .rh-roster tbody.rh-grupo th.rh-grupo-titulo {
            padding: 1.4rem 0.75rem 0.5rem;
            border-bottom: 1px solid var(--rule-2);
            font-size: 1rem;
            font-weight: 700;
            font-stretch: 80%;
            color: var(--navy);
            background: var(--paper);
        }

        .rh-roster tbody.rh-grupo tr:first-child:hover > th {
            background: var(--paper);
        }

        .rh-grupo-titulo .rh-count {
            margin-left: 0.4rem;
            font-weight: 500;
            font-stretch: 100%;
            font-size: 0.8rem;
            color: var(--ink-3);
        }

        .rh-roster tr.is-inactivo .rh-name,
        .rh-roster tr.is-inactivo .rh-saldo-num {
            color: var(--ink-3);
        }

        .rh-contacto a {
            display: block;
            font-size: 0.85rem;
            color: var(--ink-2);
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 15rem;
        }

        .rh-contacto a:hover {
            color: var(--navy);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .rh-filters .rh-seg {
            margin-left: auto;
        }

        @media (max-width: 1535px) {
            .rh-roster .c-contacto {
                display: none;
            }
        }

        @media (max-width: 760px) {
            .rh-roster {
                min-width: 0;
            }

            .rh-roster thead {
                display: none;
            }

            .rh-roster,
            .rh-roster tbody,
            .rh-roster tr {
                display: block;
            }

            .rh-roster tbody.rh-grupo tr:not(:first-child) {
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 0.35rem 1rem;
                padding: 0.85rem 0;
                border-bottom: 1px solid var(--rule);
            }

            .rh-roster tbody.rh-grupo tr:not(:first-child) > * {
                display: block;
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
            }

            .rh-roster tbody.rh-grupo th.rh-grupo-titulo {
                display: block;
                padding-left: 0;
            }

            .rh-roster .c-empleado,
            .rh-roster .c-cargo,
            .rh-roster .c-ingreso,
            .rh-roster .c-vac {
                grid-column: 1 / 2;
            }

            .rh-roster .c-estado {
                grid-column: 2 / 3;
                grid-row: 1;
                text-align: right;
            }

            .rh-roster .c-acc {
                grid-column: 1 / -1;
                width: auto;
            }

            .rh-roster tbody.rh-grupo tr > .c-contacto {
                display: none;
            }

            .rh-roster .c-acc .rh-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .rh-filters .rh-seg {
                margin-left: 0;
            }
        }
    </style>

    @php
        $critico = config('rrhh.vacaciones.saldo_critico');
        $alerta = config('rrhh.vacaciones.alerta_vencimiento_dias');
        $n = fn ($valor) => \App\Services\Rrhh\VacacionesService::numero(round($valor, 2));
        $filtrando = request()->filled('search') || request()->filled('area') || request()->filled('pendiente');
        $grupos = $empleados->getCollection()->groupBy(fn ($empleado) => $empleado->area ?: 'Sin área asignada');
        $estadoEnlace = fn ($valor) => request()->fullUrlWithQuery(['estado' => $valor, 'page' => null]);
    @endphp

    <div class="rh">

        {{-- Encabezado --}}
        <header class="rh-head">
            <div class="rh-head-main">
                <h1 class="rh-title">Empleados</h1>
                <p class="rh-sub">
                    <strong>{{ $conteo['activo'] }}</strong> {{ $conteo['activo'] === 1 ? 'activo' : 'activos' }} y
                    {{ $conteo['inactivo'] }} {{ $conteo['inactivo'] === 1 ? 'inactivo' : 'inactivos' }}{{ $filtrando ? ' con los filtros aplicados' : '' }}.
                    El saldo de vacaciones se calcula al día de hoy.
                </p>
            </div>
            <div class="rh-head-actions">
                <a href="{{ route('vacaciones.index') }}" class="rh-btn is-quiet">
                    @include('rrhh.partials.icono', ['i' => 'calendario'])
                    Control de vacaciones
                </a>
                <a href="{{ route('empleados.create') }}" class="rh-btn is-primary">
                    @include('rrhh.partials.icono', ['i' => 'mas'])
                    Nuevo empleado
                </a>
            </div>
        </header>

        {{-- Mensajes --}}
        @if (session('success'))
            <div class="rh-notice is-good" role="status">
                <span class="rh-notice-text">@include('rrhh.partials.icono', ['i' => 'ok']){{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="rh-notice is-crit" role="alert">
                <span class="rh-notice-text">@include('rrhh.partials.icono', ['i' => 'alerta']){{ session('error') }}</span>
            </div>
        @endif

        {{-- Filtros --}}
        <form method="GET" class="rh-filters" id="filtros" role="search">
            <input type="hidden" name="estado" value="{{ $estadoFiltro }}">

            <label class="rh-field is-search">
                <span class="sr-only">Buscar empleado</span>
                @include('rrhh.partials.icono', ['i' => 'buscar'])
                <input type="search" name="search" value="{{ request('search') }}" class="rh-input"
                    placeholder="Buscar por nombre o identidad">
            </label>

            <label class="rh-field">
                <span class="sr-only">Área</span>
                <select name="area" class="rh-select js-auto">
                    <option value="">Todas las áreas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                    @endforeach
                </select>
            </label>

            <label class="rh-field">
                <span class="sr-only">Saldo de vacaciones</span>
                <select name="pendiente" class="rh-select js-auto">
                    <option value="">Cualquier saldo</option>
                    <option value="con" @selected(request('pendiente') === 'con')>Con saldo</option>
                    <option value="sin" @selected(request('pendiente') === 'sin')>Sin saldo</option>
                    <option value="critico" @selected(request('pendiente') === 'critico')>Más de {{ $critico }} días</option>
                </select>
            </label>

            @if ($filtrando)
                <a href="{{ route('empleados.index', ['estado' => $estadoFiltro]) }}" class="rh-clear">Limpiar filtros</a>
            @endif

            <noscript><button type="submit" class="rh-btn is-dark">Aplicar</button></noscript>

            <nav class="rh-seg" aria-label="Estado del empleado">
                <a href="{{ $estadoEnlace('activo') }}" aria-current="{{ $estadoFiltro === 'activo' ? 'true' : 'false' }}">
                    Activos <span class="rh-count">{{ $conteo['activo'] }}</span>
                </a>
                <a href="{{ $estadoEnlace('inactivo') }}" aria-current="{{ $estadoFiltro === 'inactivo' ? 'true' : 'false' }}">
                    Inactivos <span class="rh-count">{{ $conteo['inactivo'] }}</span>
                </a>
                <a href="{{ $estadoEnlace('todos') }}" aria-current="{{ $estadoFiltro === 'todos' ? 'true' : 'false' }}">
                    Todos <span class="rh-count">{{ $conteo['todos'] }}</span>
                </a>
            </nav>
        </form>

        {{-- Directorio --}}
        <div class="rh-table-wrap">
            <table class="rh-table rh-roster">
                <caption class="sr-only">Empleados agrupados por área, con su saldo de vacaciones</caption>
                <thead>
                    <tr>
                        <th scope="col" class="c-empleado">Empleado</th>
                        <th scope="col" class="c-cargo">Cargo</th>
                        <th scope="col" class="c-contacto">Contacto</th>
                        <th scope="col" class="c-ingreso">Ingreso</th>
                        <th scope="col" class="c-vac">Vacaciones</th>
                        <th scope="col" class="c-estado">Estado</th>
                        <th scope="col" class="c-acc"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>

                @forelse ($grupos as $area => $miembros)
                    <tbody class="rh-grupo">
                        <tr>
                            <th scope="rowgroup" colspan="7" class="rh-grupo-titulo">
                                {{ $area }}<span class="rh-count">{{ $miembros->count() }}</span>
                            </th>
                        </tr>

                        @foreach ($miembros as $empleado)
                            @php
                                $estado = $estados[$empleado->id];
                                $medidor = $estado->medidor($hoy);
                                $proximo = $estado->proximoVencimiento;
                                $venceProntoDias = $proximo ? (int) $hoy->diffInDays($proximo['fecha']) : null;
                                $activo = $empleado->estado === 'activo';
                                $anios = $empleado->aniosServicio();
                            @endphp
                            <tr class="{{ $activo ? '' : 'is-inactivo' }}">
                                <th scope="row" class="c-empleado">
                                    <a href="{{ route('vacaciones.historial', $empleado) }}" class="rh-name" title="Ver historial de vacaciones">{{ $empleado->nombre_completo }}</a>
                                    <span class="rh-meta num">{{ $empleado->identidad ?: 'Sin identidad registrada' }}</span>
                                </th>

                                <td class="c-cargo">
                                    {{ $empleado->cargo ?: '—' }}
                                </td>

                                <td class="c-contacto rh-contacto">
                                    @if ($empleado->telefono)
                                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $empleado->telefono) }}" class="num">{{ $empleado->telefono }}</a>
                                    @endif
                                    @if ($empleado->correo)
                                        <a href="mailto:{{ $empleado->correo }}" title="{{ $empleado->correo }}">{{ $empleado->correo }}</a>
                                    @endif
                                    @if (! $empleado->telefono && ! $empleado->correo)
                                        <span class="rh-meta">—</span>
                                    @endif
                                </td>

                                <td class="c-ingreso">
                                    <span class="num">{{ $empleado->fecha_contratacion->format('d-m-Y') }}</span>
                                    <span class="rh-meta">{{ $anios === 0 ? 'Menos de un año' : $anios . ' ' . ($anios === 1 ? 'año' : 'años') }}</span>
                                </td>

                                <td class="c-vac">
                                    @include('rrhh.partials.saldo', ['saldo' => $estado->saldo])
                                    @include('rrhh.partials.medidor', ['medidor' => $medidor])
                                    @if ($estado->saldo < 0)
                                        <span class="rh-chip is-info">@include('rrhh.partials.icono', ['i' => 'menos']) Adelanto</span>
                                    @elseif ($activo && $proximo && $venceProntoDias <= $alerta)
                                        <span class="rh-chip is-warn">@include('rrhh.partials.icono', ['i' => 'reloj-arena']) {{ \App\Services\Rrhh\VacacionesService::formatoDias($proximo['dias']) }} vencen el {{ $proximo['fecha']->format('d-m') }}</span>
                                    @elseif ($activo && $estado->saldo > $critico)
                                        <span class="rh-chip is-serious">@include('rrhh.partials.icono', ['i' => 'bandera']) Programar vacaciones</span>
                                    @else
                                        <span class="rh-saldo-note">{{ \App\Services\Rrhh\VacacionesService::numero($estado->diasPorLeyActual()) }} días por ley este periodo</span>
                                    @endif
                                </td>

                                <td class="c-estado">
                                    @if ($activo)
                                        <span class="rh-status">Activo</span>
                                    @else
                                        <span class="rh-status is-off">Inactivo</span>
                                        @if ($empleado->fecha_salida)
                                            <span class="rh-meta">Desde el {{ $empleado->fecha_salida->format('d-m-Y') }}</span>
                                        @endif
                                    @endif
                                </td>

                                <td class="c-acc">
                                    <div class="rh-actions">
                                        <a href="{{ route('empleados.edit', $empleado) }}" class="rh-btn is-sm">Editar</a>
                                        <a href="{{ route('vacaciones.historial', $empleado) }}" class="rh-btn is-sm is-quiet">Vacaciones</a>
                                        <button type="button" class="rh-btn is-sm is-quiet {{ $activo ? 'is-danger' : '' }} js-estado"
                                            data-action="{{ route('empleados.estado', $empleado) }}"
                                            data-nombre="{{ $empleado->nombre_completo }}"
                                            data-activo="{{ $activo ? '1' : '0' }}">
                                            {{ $activo ? 'Desactivar' : 'Reactivar' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @empty
                    <tbody>
                        <tr>
                            <td colspan="7">
                                <div class="rh-empty">
                                    @if ($filtrando || $estadoFiltro !== 'activo')
                                        <p>Ningún empleado coincide con los filtros.</p>
                                        <a href="{{ route('empleados.index') }}" class="rh-btn">Limpiar filtros</a>
                                    @else
                                        <p>Aún no hay empleados registrados.</p>
                                        <a href="{{ route('empleados.create') }}" class="rh-btn is-primary">Registrar el primer empleado</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>

        @if ($empleados->hasPages())
            <div style="margin-top: 1.25rem;">
                {{ $empleados->links() }}
            </div>
        @endif

        {{-- Confirmación de cambio de estado --}}
        <dialog class="rh-dialog" id="dialogo-estado" aria-labelledby="dialogo-estado-titulo">
            <form method="POST" action="" id="form-estado">
                @csrf
                @method('PATCH')
                <div class="rh-dialog-body">
                    <h2 id="dialogo-estado-titulo"></h2>
                    <p id="dialogo-estado-texto"></p>
                </div>
                <div class="rh-dialog-actions">
                    <button type="button" class="rh-btn" id="dialogo-estado-cancelar">Cancelar</button>
                    <button type="submit" class="rh-btn is-dark" id="dialogo-estado-confirmar"></button>
                </div>
            </form>
        </dialog>
    </div>

    <script>
        (function() {
            document.querySelectorAll('#filtros .js-auto').forEach((campo) => {
                campo.addEventListener('change', () => campo.form.requestSubmit());
            });

            const dialogo = document.getElementById('dialogo-estado');
            const form = document.getElementById('form-estado');

            document.querySelectorAll('.js-estado').forEach((boton) => {
                boton.addEventListener('click', () => {
                    const activo = boton.dataset.activo === '1';
                    const nombre = boton.dataset.nombre;

                    form.action = boton.dataset.action;
                    document.getElementById('dialogo-estado-titulo').textContent =
                        (activo ? 'Desactivar a ' : 'Reactivar a ') + nombre;
                    document.getElementById('dialogo-estado-texto').textContent = activo ?
                        'Se registrará hoy como su fecha de salida y dejará de recibir acreditaciones de vacaciones. Su historial se conserva.' :
                        'Volverá a recibir acreditaciones de vacaciones en cada aniversario laboral.';
                    document.getElementById('dialogo-estado-confirmar').textContent = activo ? 'Desactivar' : 'Reactivar';
                    dialogo.showModal();
                });
            });

            document.getElementById('dialogo-estado-cancelar').addEventListener('click', () => dialogo.close());
            dialogo.addEventListener('click', (evento) => {
                if (evento.target === dialogo) dialogo.close();
            });
        })();
    </script>

</x-app-layout>
