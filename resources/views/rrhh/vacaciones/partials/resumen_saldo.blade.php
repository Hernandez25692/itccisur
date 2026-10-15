@php
    $actual = $estado->periodoActual;
    $historico = $estado->acumuladoHistorico;
    $proximo = $estado->proximoVencimiento;
    $diasParaVencer = $proximo ? (int) today()->diffInDays($proximo['fecha']) : null;
    $alerta = config('rrhh.vacaciones.alerta_vencimiento_dias');
@endphp

<div class="vac-stats">
    <div class="vac-stat is-main {{ $estado->saldo < 0 ? 'is-negative' : '' }}">
        <div class="vac-stat-label">Vacaciones Disponible</div>
        <div class="vac-stat-value">{{ number_format($estado->saldo, 2) }} d</div>
        <div class="vac-stat-sub">
            {{ \App\Services\Rrhh\VacacionesService::formatoDias($estado->saldo) }}{{ $estado->saldo < 0 ? ' · adelantado' : '' }}
        </div>
    </div>

    @php
        $siguiente = app(\App\Services\Rrhh\VacacionesService::class)->proximaAcreditacion($empleado);
        $sinVencimiento = app(\App\Services\Rrhh\VacacionesService::class)->sinVencimiento($empleado);
    @endphp

    <div class="vac-stat">
        <div class="vac-stat-label">Días por ley</div>
        @if ($actual && $actual['dias_ley'] > 0)
            <div class="vac-stat-value">{{ \App\Services\Rrhh\VacacionesService::numero($actual['dias_ley']) }} d</div>
            <div class="vac-stat-sub">
                Acreditados el {{ $actual['inicio']->format('d-m-Y') }} ({{ $actual['numero'] }} año{{ $actual['numero'] === 1 ? '' : 's' }}).
                Próxima: {{ $siguiente['fecha']->format('d-m-Y') }} (+{{ $siguiente['dias'] }})
            </div>
        @else
            <div class="vac-stat-value">—</div>
            <div class="vac-stat-sub">
                Primer año. Acredita {{ $siguiente['dias'] }} días el {{ $siguiente['fecha']->format('d-m-Y') }}
            </div>
        @endif
    </div>

    @if ($historico)
        <div class="vac-stat {{ $historico['restante'] > 0 && $historico['vence'] ? 'is-warning' : '' }}">
            <div class="vac-stat-label">Acumulado histórico</div>
            <div class="vac-stat-value">{{ number_format($historico['restante'], 2) }} d</div>
            <div class="vac-stat-sub">
                Restante de {{ \App\Services\Rrhh\VacacionesService::numero($historico['acreditado']) }}.
                @if (! $historico['vence'])
                    No vencen.
                @elseif ($historico['vencido'] > 0)
                    Vencieron el {{ $historico['vence']->format('d-m-Y') }}
                    ({{ \App\Services\Rrhh\VacacionesService::numero($historico['vencido']) }} d)
                @else
                    Vencen el {{ $historico['vence']->format('d-m-Y') }}
                @endif
            </div>
        </div>
    @endif

    <div class="vac-stat {{ $proximo && $diasParaVencer <= $alerta ? 'is-warning' : '' }}">
        <div class="vac-stat-label">Cierre del año</div>
        @if ($proximo)
            <div class="vac-stat-value">{{ number_format($proximo['dias'], 2) }} d</div>
            <div class="vac-stat-sub">
                Vencen el {{ $proximo['fecha']->format('d-m-Y') }} si no se toman
                ({{ $diasParaVencer === 0 ? 'hoy' : 'en ' . $diasParaVencer . ' días' }})
            </div>
        @elseif ($sinVencimiento)
            <div class="vac-stat-value">—</div>
            <div class="vac-stat-sub">Su área no tiene vencimiento: los días se acumulan</div>
        @elseif ($estado->saldo < 0)
            <div class="vac-stat-value">—</div>
            <div class="vac-stat-sub">
                El adelanto se compensa con la acreditación del {{ $siguiente['fecha']->format('d-m-Y') }}
                (+{{ $siguiente['dias'] }} días){{ $siguiente['fecha']->year > today()->year ? '; mientras tanto pasa al siguiente año' : '' }}.
            </div>
        @else
            <div class="vac-stat-value">—</div>
            <div class="vac-stat-sub">Sin días por vencer</div>
        @endif
    </div>
</div>
