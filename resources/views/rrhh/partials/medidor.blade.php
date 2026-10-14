@php
    $total = $medidor['total'];
    $restante = $medidor['acumuladas'] + $medidor['periodo'];
    $pctAcumuladas = $total > 0 ? max(0, min(100, $medidor['acumuladas'] / $total * 100)) : 0;
    $pctPeriodo = $total > 0 ? max(0, min(100 - $pctAcumuladas, $medidor['periodo'] / $total * 100)) : 0;
    $n = fn ($valor) => \App\Services\Rrhh\VacacionesService::numero(round($valor, 2));

    $descripcion = $total > 0
        ? 'Quedan ' . $n($restante) . ' de ' . $n($total) . ' días acreditados'
            . ($medidor['acumuladas'] > 0
                ? ' (' . $n($medidor['acumuladas']) . ' acumuladas y ' . $n($medidor['periodo']) . ' del periodo)'
                : '')
        : 'Sin días acreditados vigentes';
@endphp
<div class="rh-meter" role="img" aria-label="{{ $descripcion }}" title="{{ $descripcion }}">
    @if ($pctAcumuladas > 0)
        <span class="rh-meter-seg is-acum" style="width: {{ round($pctAcumuladas, 2) }}%"></span>
    @endif
    @if ($pctPeriodo > 0)
        <span class="rh-meter-seg is-periodo" style="width: {{ round($pctPeriodo, 2) }}%"></span>
    @endif
</div>
