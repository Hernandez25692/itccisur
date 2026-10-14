{{-- Saldo grande en días y horas: "15 días 5 h" --}}
@php
    $partes = \App\Services\Rrhh\VacacionesService::partesDias($saldo);
    $horasTexto = \App\Services\Rrhh\VacacionesService::numero($partes['horas']);
@endphp
<div class="rh-saldo" title="{{ \App\Services\Rrhh\VacacionesService::numero(round($saldo, 4)) }} días">
    <span class="rh-saldo-num {{ $partes['negativo'] ? 'is-neg' : '' }}">{{ $partes['negativo'] ? '−' : '' }}{{ $partes['dias'] }}</span>
    <span class="rh-saldo-unit">{{ $partes['dias'] === 1 ? 'día' : 'días' }}</span>
    @if ($partes['horas'] > 0)
        <span class="rh-saldo-num is-sm {{ $partes['negativo'] ? 'is-neg' : '' }}">{{ $horasTexto }}</span>
        <span class="rh-saldo-unit">h</span>
    @endif
</div>
