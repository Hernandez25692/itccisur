@php
    $actual = $estado->periodoActual;
    $historico = $estado->acumuladoHistorico;
    $proximo = $estado->proximoVencimiento;
    $diasParaVencer = $proximo ? (int) today()->diffInDays($proximo['fecha']) : null;
    $alerta = config('rrhh.vacaciones.alerta_vencimiento_dias');
@endphp

<div class="vac-stats">
    <div class="vac-stat is-main {{ $estado->saldo < 0 ? 'is-negative' : '' }}">
        <div class="vac-stat-label">Saldo disponible</div>
        <div class="vac-stat-value">{{ number_format($estado->saldo, 2) }} d</div>
        <div class="vac-stat-sub">
            {{ \App\Services\Rrhh\VacacionesService::formatoDias($estado->saldo) }}{{ $estado->saldo < 0 ? ' · adelantado' : '' }}
        </div>
    </div>

    <div class="vac-stat">
        <div class="vac-stat-label">Periodo actual</div>
        @if ($actual)
            <div class="vac-stat-value">{{ \App\Services\Rrhh\VacacionesService::numero($actual['dias_ley']) }} d</div>
            <div class="vac-stat-sub">
                Por ley ({{ $actual['numero'] }} año{{ $actual['numero'] === 1 ? '' : 's' }}) ·
                {{ $actual['inicio']->format('d-m-Y') }} al {{ $actual['fin']->format('d-m-Y') }}
            </div>
        @else
            <div class="vac-stat-value">—</div>
            <div class="vac-stat-sub">Sin periodo registrado</div>
        @endif
    </div>

    @if ($historico)
        <div class="vac-stat {{ $historico['restante'] > 0 ? 'is-warning' : '' }}">
            <div class="vac-stat-label">Acumulado histórico</div>
            <div class="vac-stat-value">{{ number_format($historico['restante'], 2) }} d</div>
            <div class="vac-stat-sub">
                Restante de {{ \App\Services\Rrhh\VacacionesService::numero($historico['acreditado']) }} ·
                @if ($historico['vencido'] > 0)
                    venció el {{ $historico['vence']->format('d-m-Y') }}
                    ({{ \App\Services\Rrhh\VacacionesService::numero($historico['vencido']) }} d perdidos)
                @else
                    vence el {{ $historico['vence']->format('d-m-Y') }}
                @endif
            </div>
        </div>
    @endif

    <div class="vac-stat {{ $proximo && $diasParaVencer <= $alerta ? 'is-warning' : '' }}">
        <div class="vac-stat-label">Próximo vencimiento</div>
        @if ($proximo)
            <div class="vac-stat-value">{{ number_format($proximo['dias'], 2) }} d</div>
            <div class="vac-stat-sub">
                Último día: {{ $proximo['fecha']->format('d-m-Y') }}
                ({{ $diasParaVencer === 0 ? 'hoy' : 'en ' . $diasParaVencer . ' días' }})
            </div>
        @else
            <div class="vac-stat-value">—</div>
            <div class="vac-stat-sub">Sin días por vencer</div>
        @endif
    </div>
</div>
