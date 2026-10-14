<x-app-layout>

    @include('rrhh.vacaciones.partials.estilos')

    @php
        $vacaciones = app(\App\Services\Rrhh\VacacionesService::class);
        $historico = $estado->acumuladoHistorico;
        $numeroActual = $estado->periodoActual['numero'] ?? null;
        $fmt = fn ($n) => $n > 0 ? number_format($n, 2) : '—';
    @endphp

    <div class="vac-page">
        <div class="vac-container is-wide">

            {{-- Breadcrumb --}}
            <nav class="vac-breadcrumb">
                <a href="{{ route('dashboard') }}">Inicio</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <a href="{{ route('vacaciones.index') }}">Control de Vacaciones</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <span>Historial</span>
            </nav>

            @include('rrhh.vacaciones.partials.alertas')

            @if ($requiereSincronizacion)
                <div class="vac-alert is-warning" style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; align-items:center;">
                    <span>Este empleado tiene acreditaciones por aniversario pendientes de procesar.</span>
                    <form method="POST" action="{{ route('vacaciones.procesar') }}">
                        @csrf
                        <button type="submit" class="vac-btn-sm">Procesar acreditaciones</button>
                    </form>
                </div>
            @endif

            {{-- Encabezado y saldo --}}
            <div class="vac-card">
                <div class="vac-header">
                    <div class="vac-header-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="vac-header-main">
                        <h1 class="vac-header-title">Historial de vacaciones</h1>
                        <p class="vac-header-sub">
                            <span class="vac-employee-name">{{ $empleado->nombre_completo }}</span> ·
                            {{ $empleado->cargo ?: 'Sin cargo' }}{{ $empleado->area ? ' · ' . $empleado->area : '' }}<br>
                            Ingreso {{ $empleado->fecha_contratacion->format('d-m-Y') }} ·
                            {{ $empleado->aniosServicio() }} año(s) de servicio ·
                            @if ($empleado->estado === 'activo')
                                <span class="vac-badge is-success">Activo</span>
                            @else
                                <span class="vac-badge is-error">Inactivo{{ $empleado->fecha_salida ? ' desde ' . $empleado->fecha_salida->format('d-m-Y') : '' }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="vac-header-actions">
                        @if ($empleado->estado === 'activo')
                            <a href="{{ route('vacaciones.crear', $empleado) }}" class="vac-btn-save">+ Registrar</a>
                        @endif
                        <a href="{{ route('empleados.edit', $empleado) }}" class="vac-btn-cancel">Editar empleado</a>
                        <a href="{{ route('vacaciones.index') }}" class="vac-btn-cancel">Volver</a>
                    </div>
                </div>

                <div class="vac-body">
                    @include('rrhh.vacaciones.partials.resumen_saldo')

                    @if ($historico && $historico['restante'] > 0 && today()->lte($historico['vence']))
                        <div class="vac-alert is-warning" style="margin: 1.25rem 0 0;">
                            Quedan <strong>{{ number_format($historico['restante'], 2) }}</strong> día(s) de vacaciones
                            acumuladas. Se descuentan primero al registrar vacaciones y vencen el
                            <strong>{{ $historico['vence']->format('d-m-Y') }}</strong>; desde el
                            {{ $vacaciones->inicioSinAcumulacion()->format('d-m-Y') }} ya no se acumulan.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Periodos --}}
            <div class="vac-card">
                <div class="vac-card-title">
                    <h3>Periodos laborales</h3>
                    <p>Cada periodo va de un aniversario de contratación al siguiente.</p>
                </div>
                <div class="vac-table-wrap">
                    <table class="vac-table">
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>Vigencia</th>
                                <th class="num">Arrastre</th>
                                <th class="num">Días ley</th>
                                <th class="num">Ajustes +</th>
                                <th class="num">Tomado</th>
                                <th class="num">Vencido</th>
                                <th class="num">Saldo</th>
                                <th>Días vencen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estado->periodos as $periodo)
                                <tr class="{{ $periodo['numero'] === $numeroActual ? 'is-actual' : '' }}">
                                    <td class="nowrap">
                                        <strong>{{ $periodo['etiqueta'] }}</strong>
                                        <div class="vac-small">
                                            {{ $periodo['numero'] === 0 ? 'Antes del primer aniversario' : $periodo['numero'] . ' año(s) de servicio' }}
                                            @if ($periodo['numero'] === $numeroActual)
                                                · <span class="vac-badge is-info">Actual</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="nowrap">{{ $periodo['inicio']->format('d-m-Y') }} al {{ $periodo['fin']->format('d-m-Y') }}</td>
                                    <td class="num">{{ number_format($periodo['saldo_inicial'], 2) }}</td>
                                    <td class="num">{{ \App\Services\Rrhh\VacacionesService::numero($periodo['dias_ley']) }}</td>
                                    <td class="num">{{ $fmt($periodo['acreditado'] - $periodo['dias_ley']) }}</td>
                                    <td class="num">{{ $fmt($periodo['tomado']) }}</td>
                                    <td class="num {{ $periodo['vencido'] > 0 ? 'vac-neg' : '' }}">{{ $fmt($periodo['vencido']) }}</td>
                                    <td class="num {{ $periodo['saldo_final'] < 0 ? 'vac-neg' : '' }}"><strong>{{ number_format($periodo['saldo_final'], 2) }}</strong></td>
                                    <td class="nowrap">{{ $periodo['dias_ley'] > 0 ? $periodo['vence']->format('d-m-Y') : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="vac-empty">Aún no hay periodos laborales registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Estado de cuenta --}}
            <div class="vac-card">
                <div class="vac-card-title">
                    <h3>Estado de cuenta</h3>
                    <p>Orden cronológico. Se descuentan primero los días que vencen antes.</p>
                </div>
                <div class="vac-table-wrap">
                    <table class="vac-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th class="num">Suma</th>
                                <th class="num">Descuenta</th>
                                <th class="num">Vence</th>
                                <th class="num">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estado->kardex as $fila)
                                <tr class="{{ $fila['programado'] ? 'is-programado' : '' }}">
                                    <td class="nowrap">{{ $fila['fecha']->format('d-m-Y') }}</td>
                                    <td>
                                        {{ $fila['concepto'] }}
                                        @if ($fila['programado'])
                                            <span class="vac-badge is-info">Programado</span>
                                        @endif
                                        @if ($fila['adelanto'] > 0)
                                            <span class="vac-badge is-warning">Adelanto {{ \App\Services\Rrhh\VacacionesService::numero($fila['adelanto']) }} d</span>
                                        @endif
                                    </td>
                                    <td class="num vac-pos">{{ $fila['credito'] > 0 ? '+' . number_format($fila['credito'], 2) : '' }}</td>
                                    <td class="num vac-neg">{{ $fila['debito'] > 0 ? '−' . number_format($fila['debito'], 2) : '' }}</td>
                                    <td class="num vac-neg">{{ $fila['vencido'] > 0 ? '−' . number_format($fila['vencido'], 2) : '' }}</td>
                                    <td class="num {{ $fila['saldo'] < 0 ? 'vac-neg' : '' }}"><strong>{{ number_format($fila['saldo'], 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="vac-empty">Sin movimientos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Movimientos --}}
            <div class="vac-card">
                <div class="vac-card-title">
                    <h3>Movimientos registrados</h3>
                    <p>Vacaciones, permisos y ajustes. Los anulados no afectan el saldo.</p>
                </div>
                <div class="vac-table-wrap">
                    <table class="vac-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th class="num">Horas</th>
                                <th class="num">Días</th>
                                <th>Motivo</th>
                                <th>Comprobante</th>
                                <th>Estado</th>
                                <th class="center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movimientos as $mov)
                                <tr class="{{ $mov->estado === 'anulado' ? 'is-muted' : '' }}">
                                    <td class="nowrap">
                                        <span class="vac-desc">
                                            @switch($mov->tipo_movimiento)
                                                @case('vacacion_dias')
                                                    <span class="vac-badge is-success">Vacación (días)</span>
                                                @break

                                                @case('vacacion_horas')
                                                    <span class="vac-badge is-info">Vacación (horas)</span>
                                                @break

                                                @case('permiso_especial')
                                                    <span class="vac-badge is-warning">Permiso especial</span>
                                                @break

                                                @default
                                                    <span class="vac-badge">Ajuste {{ (float) $mov->dias_equivalentes >= 0 ? 'a favor' : 'en contra' }}</span>
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="nowrap">
                                        <span class="vac-desc">
                                            {{ $mov->fecha_inicio->format('d-m-Y') }}
                                            @if ($mov->fecha_fin && !$mov->fecha_fin->equalTo($mov->fecha_inicio))
                                                → {{ $mov->fecha_fin->format('d-m-Y') }}
                                            @endif
                                        </span>
                                        @if ($mov->esPorHoras())
                                            <div class="vac-small">{{ $mov->horaInicioCorta() }} → {{ $mov->horaFinCorta() }}</div>
                                        @endif
                                    </td>
                                    <td class="num">{{ $mov->esPorHoras() ? \App\Services\Rrhh\VacacionesService::numero($mov->horas_equivalentes) : '' }}</td>
                                    <td class="num">
                                        {{ \App\Services\Rrhh\VacacionesService::numero($mov->dias_equivalentes) }}
                                        @if ($mov->tipo_movimiento === 'permiso_especial')
                                            <div class="vac-small">no descuenta</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $mov->motivo }}
                                        @if ($mov->observacion)
                                            <div class="vac-small">{!! nl2br(e($mov->observacion)) !!}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($mov->archivo_comprobante)
                                            <a href="{{ route('vacaciones.comprobante', $mov) }}" target="_blank"
                                                class="vac-btn-sm">Ver archivo</a>
                                        @endif
                                    </td>
                                    <td class="nowrap">
                                        @if ($mov->estado === 'anulado')
                                            <span class="vac-badge is-error">Anulado</span>
                                        @else
                                            <span class="vac-badge is-success">{{ \App\Models\RrhhVacacionesMovimiento::ESTADOS[$mov->estado] ?? $mov->estado }}</span>
                                        @endif
                                        @if ($mov->es_adelanto)
                                            <span class="vac-badge is-warning">Adelanto</span>
                                        @endif
                                        @if ($mov->aprobado_por)
                                            <div class="vac-small">{{ $mov->aprobado_por }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($mov->estado !== 'anulado')
                                            <div class="vac-actions">
                                                <a href="{{ route('vacaciones.editar', $mov) }}" class="vac-btn-sm">Editar</a>
                                                <button type="button" class="vac-btn-sm is-danger js-anular"
                                                    data-action="{{ route('vacaciones.anular', $mov) }}"
                                                    data-descripcion="{{ $mov->descripcion() }}">
                                                    Anular
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="vac-empty">No hay registros de vacaciones.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Bitácora --}}
            <div class="vac-card">
                <div class="vac-card-title">
                    <h3>Bitácora de cambios</h3>
                    <p>Registro de acciones realizadas sobre las vacaciones del empleado.</p>
                </div>
                <div class="vac-table-wrap">
                    <table class="vac-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Acción</th>
                                <th>Detalle</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bitacora as $log)
                                <tr>
                                    <td class="nowrap">{{ $log->fecha_evento?->format('d-m-Y H:i') }}</td>
                                    <td class="nowrap"><strong>{{ str_replace('_', ' ', $log->accion) }}</strong></td>
                                    <td>{{ $log->detalle }}</td>
                                    <td class="nowrap">{{ $log->usuario }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="vac-empty">No hay registros en la bitácora.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal anular --}}
    <div class="vac-modal" id="modalAnular">
        <div class="vac-modal-box">
            <div class="vac-modal-head">Anular movimiento</div>
            <div class="vac-modal-body">
                <form method="POST" action="" id="formAnular">
                    @csrf
                    @method('PATCH')
                    <p class="vac-small" id="anularDescripcion" style="margin: 0 0 1rem;"></p>
                    <div class="vac-field">
                        <label class="vac-label" for="motivo_anulacion">Motivo de la anulación <span class="req">*</span></label>
                        <textarea name="motivo_anulacion" id="motivo_anulacion" class="vac-textarea" required
                            maxlength="500" placeholder="Ej. Se registró dos veces, el empleado no tomó las vacaciones..."></textarea>
                    </div>
                    <p class="vac-hint">El movimiento queda en el historial como anulado y el saldo se recalcula.</p>
                    <div class="vac-footer">
                        <button type="button" class="vac-btn-cancel" id="cancelarAnular">Cancelar</button>
                        <button type="submit" class="vac-btn-save" style="background: var(--error);">Anular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('modalAnular');
            const form = document.getElementById('formAnular');
            const cerrar = () => modal.classList.remove('is-open');

            document.querySelectorAll('.js-anular').forEach((boton) => {
                boton.addEventListener('click', () => {
                    form.action = boton.dataset.action;
                    document.getElementById('anularDescripcion').textContent = boton.dataset.descripcion;
                    modal.classList.add('is-open');
                    document.getElementById('motivo_anulacion').focus();
                });
            });

            document.getElementById('cancelarAnular').addEventListener('click', cerrar);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) cerrar();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') cerrar();
            });
        })();
    </script>

</x-app-layout>
