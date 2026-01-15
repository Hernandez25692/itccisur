<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Ruta de Cobranza</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 14px;
            margin: 0;
        }

        .muted {
            color: #555;
            font-size: 11px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 6px;
        }

        .empresa {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        .empresa:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
        }

        .maps {
            color: #0b5ed7;
            text-decoration: none;
            font-weight: bold;
        }

        .estado {
            margin-top: 8px;
            border-top: 1px dashed #ccc;
            padding-top: 6px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO --}}
    <h1>Ruta de Cobranza</h1>
    <div class="box">
        <div><span class="label">Ruta:</span> {{ $ruta->nombre }}</div>
        <div><span class="label">Fecha:</span> {{ $ruta->fecha_ruta->format('d/m/Y') }}</div>
        <div><span class="label">Estado:</span> {{ strtoupper($ruta->estado) }}</div>
    </div>

    {{-- LISTADO DE EMPRESAS --}}
    @foreach ($ruta->empresas as $i => $e)
        <div class="empresa">

            <h2>{{ $i + 1 }}. {{ $e->nombre_empresa }}</h2>
            <p class="muted">
                {{ $e->direccion ?? '—' }} · {{ $e->ciudad ?? '—' }} · {{ $e->barrio_colonia ?? '—' }}
            </p>

            {{-- CONTACTOS --}}
            <p>
                <span class="label">Teléfonos:</span>
                @forelse($e->telefonosFijos as $t)
                    {{ $t->telefono }}@if (!$loop->last)
                        ,
                    @endif
                    @empty
                        —
                    @endforelse
                    /
                    @forelse($e->celulares as $c)
                        {{ $c->celular }}@if (!$loop->last)
                            ,
                        @endif
                        @empty
                            —
                        @endforelse
                    </p>

                    <p>
                        <span class="label">Correos:</span>
                        @forelse($e->correos as $c)
                            {{ $c->correo }}@if (!$loop->last)
                                ,
                            @endif
                            @empty
                                —
                            @endforelse
                        </p>

                        {{-- MORA --}}
                        <p>
                            <span class="label">Mora:</span>
                            {{ $e->meses_mora }} meses ·
                            <strong>L. {{ number_format($e->valor_mora, 2) }}</strong>
                        </p>

                        {{-- MAPS --}}
                        @if ($e->latitud && $e->longitud)
                            <p>
                                📍
                                <a class="maps" href="https://www.google.com/maps?q={{ $e->latitud }},{{ $e->longitud }}"
                                    target="_blank">
                                    Abrir en Google Maps
                                </a><br>
                                <span class="muted">
                                    ({{ $e->latitud }}, {{ $e->longitud }})
                                </span>
                            </p>
                        @else
                            <p class="muted">📍 Sin coordenadas registradas</p>
                        @endif

                        {{-- CONTROL MANUAL --}}
                        <div class="estado">
                            <p><span class="label">Estado de visita:</span> ________________________________</p>
                            <p><span class="label">Observación:</span></p>
                            <p>__________________________________________________________</p>
                        </div>

                    </div>
                @endforeach

                {{-- PIE --}}
                <div class="footer">
                    CCISUR · Sistema de Cobranza · Documento generado el {{ now()->format('d/m/Y H:i') }}
                </div>

            </body>

            </html>
