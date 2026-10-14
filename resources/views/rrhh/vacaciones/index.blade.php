<x-app-layout>

    @include('rrhh.partials.ui')

    <style>
        .rh-planilla {
            min-width: 1040px;
        }

        .rh-planilla .c-nombre {
            position: sticky;
            left: 0;
            z-index: 1;
            min-width: 13.5rem;
            max-width: 16rem;
            box-shadow: inset -1px 0 0 var(--rule);
        }

        .rh-planilla thead .c-nombre {
            z-index: 2;
        }

        .rh-planilla .c-saldo {
            min-width: 12rem;
            padding-left: 1rem;
        }

        .rh-planilla .c-mes {
            width: 2.45rem;
            padding-left: 1px;
            padding-right: 1px;
            text-align: center;
            vertical-align: middle;
        }

        .rh-planilla thead .c-mes {
            text-align: center;
            padding-left: 0;
            padding-right: 0;
        }

        .rh-planilla thead .c-mes abbr {
            text-decoration: none;
        }

        .rh-planilla thead .c-mes.is-hoy {
            color: var(--navy);
            box-shadow: inset 0 -2px 0 var(--gold);
        }

        .rh-planilla tbody .c-mes.is-hoy {
            background: var(--gold-wash);
        }

        .rh-planilla .c-total {
            vertical-align: middle;
            text-align: right;
            font-weight: 600;
            padding-left: 1rem;
            white-space: nowrap;
        }

        .rh-planilla .c-acc {
            vertical-align: middle;
        }

        .rh-cel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2rem;
            margin: 0 auto;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .rh-cel.h-1,
        .rh-cel.h-2,
        .rh-cel.h-3,
        .rh-cel.h-4 {
            box-shadow: none;
        }

        .rh-cel[data-tip] {
            cursor: default;
        }

        .rh-cel[data-tip]:hover {
            outline: 2px solid var(--navy);
            outline-offset: 1px;
        }

        .rh-dot {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            box-shadow: 0 0 0 1.5px var(--paper);
        }

        .rh-key-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gold);
            display: inline-block;
        }

        .rh-planilla-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.6rem 1.5rem;
            padding: 0 0 0.75rem;
        }

        @media (max-width: 760px) {
            .rh-planilla {
                min-width: 0;
            }

            .rh-planilla thead {
                display: none;
            }

            .rh-planilla,
            .rh-planilla tbody {
                display: block;
            }

            .rh-planilla tbody tr {
                display: grid;
                grid-template-columns: repeat(12, minmax(0, 1fr));
                column-gap: 2px;
                padding: 0.9rem 0;
                border-bottom: 1px solid var(--rule);
            }

            .rh-planilla tbody tr > * {
                border-bottom: none !important;
                padding: 0.2rem 0 !important;
                background: transparent !important;
            }

            .rh-planilla .c-nombre {
                position: static;
                box-shadow: none;
                grid-column: 1 / -1;
                max-width: none;
            }

            .rh-planilla .c-saldo {
                grid-column: 1 / -1;
                padding-bottom: 0.5rem !important;
            }

            .rh-planilla .c-mes {
                width: auto;
            }

            .rh-planilla .c-mes::before {
                content: attr(data-mes);
                display: block;
                font-size: 0.65rem;
                color: var(--ink-3);
                margin-bottom: 2px;
            }

            .rh-cel {
                width: 100%;
            }

            .rh-planilla .c-total {
                grid-column: 1 / 5;
                text-align: left;
                padding-top: 0.6rem !important;
            }

            .rh-planilla .c-total::before {
                content: 'Total del año: ';
                font-weight: 400;
                color: var(--ink-3);
            }

            .rh-planilla .c-acc {
                grid-column: 5 / -1;
                padding-top: 0.6rem !important;
            }
        }
    </style>

    @php
        $servicio = app(\App\Services\Rrhh\VacacionesService::class);
        $critico = config('rrhh.vacaciones.saldo_critico');
        $alerta = config('rrhh.vacaciones.alerta_vencimiento_dias');
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $mesActual = $anio === $hoy->year ? $hoy->month : null;
        $filtrando = request()->filled('search') || request()->filled('area') || request()->filled('pendiente') || request()->filled('orden');
        $pendiente = request('pendiente');
        $hayAcumuladas = $filas->contains(fn ($fila) => $fila['medidor']['acumuladas'] > 0);
        $n = fn ($valor) => \App\Services\Rrhh\VacacionesService::numero(round($valor, 2));
        $n1 = fn ($valor) => \App\Services\Rrhh\VacacionesService::numero(round($valor, 1));
        $dh = function ($valor) {
            $p = \App\Services\Rrhh\VacacionesService::partesDias($valor);

            return ($p['negativo'] ? '−' : '') . $p['dias'] . ' ' . ($p['dias'] === 1 ? 'día' : 'días')
                . ($p['horas'] > 0 ? ' y ' . \App\Services\Rrhh\VacacionesService::numero($p['horas']) . ' h' : '');
        };
        $corto = fn ($valor) => \App\Services\Rrhh\VacacionesService::formatoDias($valor);
        $enlace = fn (array $cambios) => request()->fullUrlWithQuery($cambios);
    @endphp

    <div class="rh">

        {{-- Encabezado --}}
        <header class="rh-head">
            <div class="rh-head-main">
                <div class="rh-title-row">
                    <h1 class="rh-title">Vacaciones</h1>
                    <nav class="rh-year" aria-label="Año de la planilla">
                        <a class="rh-year-step" href="{{ $enlace(['anio' => $anio - 1]) }}"
                            aria-label="Ver {{ $anio - 1 }}">@include('rrhh.partials.icono', ['i' => 'anterior'])</a>
                        <span class="rh-year-num" aria-current="page">{{ $anio }}</span>
                        <a class="rh-year-step" href="{{ $enlace(['anio' => $anio + 1]) }}"
                            aria-label="Ver {{ $anio + 1 }}">@include('rrhh.partials.icono', ['i' => 'siguiente'])</a>
                    </nav>
                </div>
                <p class="rh-sub">
                    Al {{ $hoy->translatedFormat('j \d\e F \d\e Y') }},
                    {{ $resumen['empleados'] }} {{ $resumen['empleados'] === 1 ? 'colaborador activo tiene' : 'colaboradores activos suman' }}
                    <strong>{{ $dh($resumen['disponibles']) }} disponibles</strong>.
                    Los meses muestran lo tomado en {{ $anio }}.
                </p>
            </div>
            <div class="rh-head-actions">
                <form method="POST" action="{{ route('vacaciones.procesar') }}">
                    @csrf
                    <button type="submit" class="rh-btn"
                        title="Acredita los aniversarios pendientes y recalcula los saldos. Se puede ejecutar varias veces sin duplicar.">
                        @include('rrhh.partials.icono', ['i' => 'actualizar'])
                        Procesar acreditaciones
                    </button>
                </form>
                <a href="{{ route('empleados.index') }}" class="rh-btn is-quiet">Empleados</a>
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

        @if ($pendientesAcreditar > 0)
            <div class="rh-notice is-warn">
                <span class="rh-notice-text">
                    @include('rrhh.partials.icono', ['i' => 'alerta'])
                    <span>
                        {{ $pendientesAcreditar }} {{ $pendientesAcreditar === 1 ? 'colaborador tiene' : 'colaboradores tienen' }}
                        aniversarios sin acreditar. Los saldos no estarán al día hasta procesarlos.
                    </span>
                </span>
                <form method="POST" action="{{ route('vacaciones.procesar') }}">
                    @csrf
                    <button type="submit" class="rh-btn is-dark is-sm">Procesar ahora</button>
                </form>
            </div>
        @endif

        {{-- Lo que requiere atención --}}
        <ul class="rh-attn" aria-label="Requieren atención">
            @if ($resumen['debe_salir'] > 0)
                <li>
                    <a href="{{ $enlace(['pendiente' => $pendiente === 'critico' ? null : 'critico']) }}"
                        class="rh-attn-item is-tone-serious {{ $pendiente === 'critico' ? 'is-active' : '' }}"
                        aria-pressed="{{ $pendiente === 'critico' ? 'true' : 'false' }}">
                        @include('rrhh.partials.icono', ['i' => 'bandera'])
                        <span><strong>{{ $resumen['debe_salir'] }}</strong> con más de {{ $critico }} días: deben programar vacaciones</span>
                    </a>
                </li>
            @endif
            @if ($resumen['vence_pronto'] > 0)
                <li>
                    <a href="{{ $enlace(['pendiente' => $pendiente === 'por_vencer' ? null : 'por_vencer']) }}"
                        class="rh-attn-item is-tone-warn {{ $pendiente === 'por_vencer' ? 'is-active' : '' }}"
                        aria-pressed="{{ $pendiente === 'por_vencer' ? 'true' : 'false' }}">
                        @include('rrhh.partials.icono', ['i' => 'reloj-arena'])
                        <span><strong>{{ $resumen['vence_pronto'] }}</strong> pierden {{ $dh($resumen['dias_por_vencer']) }} en los próximos {{ $alerta }} días si no los toman</span>
                    </a>
                </li>
            @endif
            @if ($resumen['adelanto'] > 0)
                <li>
                    <a href="{{ $enlace(['pendiente' => $pendiente === 'adelanto' ? null : 'adelanto']) }}"
                        class="rh-attn-item is-tone-crit {{ $pendiente === 'adelanto' ? 'is-active' : '' }}"
                        aria-pressed="{{ $pendiente === 'adelanto' ? 'true' : 'false' }}">
                        @include('rrhh.partials.icono', ['i' => 'menos'])
                        <span><strong>{{ $resumen['adelanto'] }}</strong> con vacaciones adelantadas (saldo negativo)</span>
                    </a>
                </li>
            @endif
            @if ($resumen['debe_salir'] + $resumen['vence_pronto'] + $resumen['adelanto'] === 0 && $resumen['empleados'] > 0)
                <li class="rh-attn-item is-tone-good">
                    @include('rrhh.partials.icono', ['i' => 'ok'])
                    <span>Nadie tiene saldos críticos, días por vencer ni adelantos.</span>
                </li>
            @endif
        </ul>

        {{-- Filtros --}}
        <form method="GET" class="rh-filters" id="filtros" role="search">
            <input type="hidden" name="anio" value="{{ $anio }}">

            <label class="rh-field is-search">
                <span class="sr-only">Buscar colaborador</span>
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
                <span class="sr-only">Saldo</span>
                <select name="pendiente" class="rh-select js-auto">
                    <option value="">Cualquier saldo</option>
                    <option value="con" @selected($pendiente === 'con')>Con saldo</option>
                    <option value="sin" @selected($pendiente === 'sin')>Sin saldo</option>
                    <option value="critico" @selected($pendiente === 'critico')>Más de {{ $critico }} días</option>
                    <option value="por_vencer" @selected($pendiente === 'por_vencer')>Con días por vencer</option>
                    <option value="adelanto" @selected($pendiente === 'adelanto')>Con adelanto</option>
                </select>
            </label>

            <label class="rh-field">
                <span class="sr-only">Ordenar</span>
                <select name="orden" class="rh-select js-auto">
                    <option value="">Ordenar por nombre</option>
                    <option value="saldo" @selected(request('orden') === 'saldo')>Mayor saldo primero</option>
                    <option value="vence" @selected(request('orden') === 'vence')>Vencimiento más próximo</option>
                </select>
            </label>

            <div class="rh-filters-end">
                @if ($filtrando)
                    <a href="{{ route('vacaciones.index', ['anio' => $anio]) }}" class="rh-clear">Limpiar filtros</a>
                @endif
                <button type="submit" class="rh-btn is-dark">Aplicar</button>
            </div>
        </form>

        {{-- Leyenda --}}
        <div class="rh-planilla-top">
            <div class="rh-legend" aria-hidden="true">
                <span class="rh-legend-item">
                    Días tomados por mes
                    <span class="rh-scale">
                        <span class="h-1">&lt;1</span><span class="h-2">1–3</span><span class="h-3">3–6</span><span class="h-4">6+</span>
                    </span>
                </span>
                <span class="rh-legend-item"><span class="rh-key-dot"></span> Permiso especial (no descuenta)</span>
            </div>
            <div class="rh-legend" aria-hidden="true">
                <span class="rh-legend-item"><span class="rh-key is-bar" style="background: var(--serie-periodo)"></span> Disponible del periodo</span>
                @if ($hayAcumuladas)
                    <span class="rh-legend-item"><span class="rh-key is-bar" style="background: var(--serie-acum)"></span> Acumuladas, vencen el {{ $venceAcumuladas->format('d-m-Y') }}</span>
                @endif
                <span class="rh-legend-item"><span class="rh-key is-bar" style="background: var(--pista)"></span> Ya tomado</span>
            </div>
        </div>

        {{-- Planilla --}}
        <div class="rh-table-wrap">
            <table class="rh-table rh-planilla">
                <caption class="sr-only">
                    Saldo de vacaciones de cada colaborador y días tomados por mes en {{ $anio }}
                </caption>
                <thead>
                    <tr>
                        <th scope="col" class="c-nombre">Colaborador</th>
                        <th scope="col" class="c-saldo">Saldo disponible</th>
                        @foreach ($meses as $i => $mes)
                            <th scope="col" class="c-mes {{ $mesActual === $i + 1 ? 'is-hoy' : '' }}">
                                <abbr title="{{ ucfirst($mes) }}">{{ ucfirst(mb_substr($mes, 0, 3)) }}</abbr>
                            </th>
                        @endforeach
                        <th scope="col" class="c-total">Total {{ $anio }}</th>
                        <th scope="col" class="c-acc"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($filas as $fila)
                        @php
                            $empleado = $fila['empleado'];
                            $estado = $fila['estado'];
                            $proximo = $estado->proximoVencimiento;
                            $actual = $estado->periodoActual;
                            $anios = $empleado->aniosServicio();
                            $servicioTexto = $anios === 0 ? 'menos de un año' : $anios . ' ' . ($anios === 1 ? 'año' : 'años');
                        @endphp
                        <tr>
                            <th scope="row" class="c-nombre">
                                <a href="{{ route('vacaciones.historial', $empleado) }}" class="rh-name" title="Ver historial y estado de cuenta">{{ $empleado->nombre_completo }}</a>
                                <span class="rh-meta">{{ $empleado->cargo ? $empleado->cargo . ', ' : '' }}{{ $servicioTexto }} de servicio</span>
                            </th>

                            <td class="c-saldo">
                                @include('rrhh.partials.saldo', ['saldo' => $estado->saldo])
                                @include('rrhh.partials.medidor', ['medidor' => $fila['medidor']])

                                @if ($fila['adelanto'] || $fila['vence_pronto'] || $fila['debe_salir'])
                                    <div class="rh-chips">
                                        @if ($fila['adelanto'])
                                            <span class="rh-chip is-crit">@include('rrhh.partials.icono', ['i' => 'menos']) Adelanto de {{ $corto(abs($estado->saldo)) }}</span>
                                        @endif
                                        @if ($fila['vence_pronto'])
                                            <span class="rh-chip is-warn">@include('rrhh.partials.icono', ['i' => 'reloj-arena']) {{ $corto($proximo['dias']) }} vencen el {{ $proximo['fecha']->format('d-m') }}</span>
                                        @endif
                                        @if ($fila['debe_salir'])
                                            <span class="rh-chip is-serious">@include('rrhh.partials.icono', ['i' => 'bandera']) Programar vacaciones</span>
                                        @endif
                                    </div>
                                @elseif ($proximo)
                                    <span class="rh-saldo-note">Vencen el {{ $proximo['fecha']->format('d-m-Y') }}</span>
                                @elseif ($actual)
                                    <span class="rh-saldo-note">
                                        Acredita {{ $servicio->diasPorAntiguedad($actual['numero'] + 1) }} días el
                                        {{ $actual['fin']->addDay()->format('d-m-Y') }}
                                    </span>
                                @endif
                            </td>

                            @foreach ($meses as $i => $mes)
                                @php
                                    $dato = $fila['meses'][$i + 1];
                                    $dias = $dato['dias'];
                                    $nivel = $dias <= 0 ? '' : ($dias < 1 ? 'h-1' : ($dias < 3 ? 'h-2' : ($dias < 6 ? 'h-3' : 'h-4')));
                                    $partes = [];
                                    if ($dias > 0) {
                                        $partes[] = $n($dias) . ' ' . ($dias == 1 ? 'día' : 'días') . ' de vacaciones';
                                    }
                                    if ($dato['permisos'] > 0) {
                                        $partes[] = $dato['permisos'] . ' ' . ($dato['permisos'] === 1 ? 'permiso especial' : 'permisos especiales')
                                            . ' (' . $n($dato['permisos_horas']) . ' h, no descuentan)';
                                    }
                                    $detalle = $partes ? ucfirst($mes) . ' ' . $anio . ': ' . implode(' y ', $partes) . '.' : null;
                                @endphp
                                <td class="c-mes {{ $mesActual === $i + 1 ? 'is-hoy' : '' }}" data-mes="{{ ucfirst(mb_substr($mes, 0, 3)) }}">
                                    <span class="rh-cel {{ $nivel }}" @if ($detalle) data-tip="{{ $detalle }}" @endif>
                                        <span aria-hidden="true">{{ $dias > 0 ? $n1($dias) : '' }}</span>
                                        @if ($dato['permisos'] > 0)
                                            <i class="rh-dot" aria-hidden="true"></i>
                                        @endif
                                        @if ($detalle)
                                            <span class="sr-only">{{ $detalle }}</span>
                                        @endif
                                    </span>
                                </td>
                            @endforeach

                            <td class="c-total num">{{ $fila['tomado_anio'] > 0 ? $corto($fila['tomado_anio']) : '—' }}</td>

                            <td class="c-acc">
                                <div class="rh-actions">
                                    <a href="{{ route('vacaciones.crear', $empleado) }}" class="rh-btn is-sm"
                                        aria-label="Registrar vacaciones o permiso para {{ $empleado->nombre_completo }}">
                                        @include('rrhh.partials.icono', ['i' => 'mas'])
                                        Registrar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16">
                                <div class="rh-empty">
                                    @if ($filtrando)
                                        <p>Ningún colaborador coincide con los filtros.</p>
                                        <a href="{{ route('vacaciones.index', ['anio' => $anio]) }}" class="rh-btn">Limpiar filtros</a>
                                    @else
                                        <p>Aún no hay colaboradores activos registrados.</p>
                                        <a href="{{ route('empleados.create') }}" class="rh-btn is-primary">Registrar empleado</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="rh-foot">
            Los días por ley se acreditan en cada aniversario laboral (10, 12, 15 y 20 días según los años de servicio).
            Desde el {{ $servicio->inicioSinAcumulacion()->format('d-m-Y') }} no se acumulan: los días de cada periodo
            vencen al siguiente aniversario y las vacaciones acumuladas vencen el {{ $venceAcumuladas->format('d-m-Y') }}.
            Al registrar vacaciones se descuentan primero los días que vencen antes. Los domingos no se descuentan y
            8 horas equivalen a 1 día.
        </p>

        <div class="rh-tip" id="rh-tip" role="tooltip" hidden></div>
    </div>

    <script>
        (function() {
            // Los filtros de lista se aplican al cambiarlos
            document.querySelectorAll('#filtros .js-auto').forEach((campo) => {
                campo.addEventListener('change', () => campo.form.requestSubmit());
            });

            // Detalle de cada mes al pasar el cursor
            const tip = document.getElementById('rh-tip');

            const mostrar = (celda) => {
                tip.textContent = celda.dataset.tip;
                tip.hidden = false;
                const caja = celda.getBoundingClientRect();
                const ancho = tip.offsetWidth;
                const izquierda = Math.min(window.innerWidth - ancho - 8, Math.max(8, caja.left + caja.width / 2 - ancho / 2));
                const arriba = caja.top - tip.offsetHeight - 8;
                tip.style.left = izquierda + 'px';
                tip.style.top = (arriba < 8 ? caja.bottom + 8 : arriba) + 'px';
            };

            document.querySelectorAll('.rh-cel[data-tip]').forEach((celda) => {
                celda.addEventListener('pointerenter', () => mostrar(celda));
                celda.addEventListener('pointerleave', () => tip.hidden = true);
            });

            window.addEventListener('scroll', () => tip.hidden = true, true);
        })();
    </script>

</x-app-layout>
