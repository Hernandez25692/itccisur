<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ruta de Cobranza</title>

    <style>
        @page {
            size: letter portrait;
            margin: 18mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ================= HEADER ================= */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .logo {
            width: 140px;
        }

        .header-right {
            text-align: right;
            font-size: 9pt;
            line-height: 1.4;
        }

        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase;
        }

        /* ================= INFO RUTA ================= */
        .box {
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 10px;
            font-size: 9pt;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px;
        }

        .label {
            font-weight: bold;
            width: 130px;
        }

        /* ================= EMPRESA ================= */
        .empresa {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .empresa h2 {
            font-size: 10.5pt;
            margin: 0 0 4px 0;
            font-weight: bold;
        }

        .muted {
            font-size: 8.5pt;
            color: #444;
            margin-bottom: 4px;
        }

        .row {
            margin-bottom: 3px;
        }

        .maps {
            font-weight: bold;
            text-decoration: none;
        }

        /* ================= CONTROL ================= */
        .control {
            margin-top: 6px;
            border-top: 1px dashed #000;
            padding-top: 6px;
            font-size: 9pt;
        }

        .line {
            border-bottom: 1px solid #000;
            height: 14px;
            margin-top: 4px;
        }

        /* ================= FOOTER ================= */
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #555;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ public_path('storage/logos/logop.png') }}" class="logo">
            </td>
            <td class="header-right">
                <strong>CÁMARA DE COMERCIO E INDUSTRIAS DEL SUR</strong><br>
                Ciudad Balcanes, Parque Industrial, Choluteca<br>
                Tel: 3315-0844 · RTN: 06019008150622<br>
                info@ccisur.org
            </td>
        </tr>
    </table>

    <div class="title">Ruta de Cobranza</div>

    {{-- ================= INFO RUTA ================= --}}
    <div class="box">
        <table class="info-table">
            <tr>
                <td class="label">Ruta:</td>
                <td>{{ $ruta->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Fecha:</td>
                <td>{{ $ruta->fecha_ruta->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Estado:</td>
                <td>{{ strtoupper($ruta->estado) }}</td>
            </tr>
            <tr>
                <td class="label">Total Empresas:</td>
                <td>{{ $ruta->empresas->count() }}</td>
            </tr>
        </table>
    </div>

    {{-- ================= EMPRESAS ================= --}}
    @foreach ($ruta->empresas as $i => $e)
        <div class="empresa">

            <h2>{{ $i + 1 }}. {{ $e->nombre_empresa }}</h2>

            <div class="muted">
                {{ $e->direccion ?? '—' }},
                {{ $e->barrio_colonia ?? '—' }},
                {{ $e->ciudad ?? '—' }}
            </div>

            <div class="row">
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
                    </div>

                    <div class="row">
                        <span class="label">Correos:</span>
                        @forelse($e->correos as $c)
                            {{ $c->correo }}@if (!$loop->last)
                                ,
                            @endif
                            @empty
                                —
                            @endforelse
                        </div>

                        <div class="row">
                            <span class="label">Mora:</span>
                            {{ $e->meses_mora }} meses —
                            <strong>L. {{ number_format($e->valor_mora, 2) }}</strong>
                        </div>

                        {{-- MAPS --}}
                        @if ($e->latitud && $e->longitud)
                            <p>
                                
                                <a class="maps" href="https://www.google.com/maps?q={{ $e->latitud }},{{ $e->longitud }}"
                                    target="_blank">
                                    Abrir en Google Maps
                                </a><br>
                                <span class="muted">
                                    ({{ $e->latitud }}, {{ $e->longitud }})
                                </span>
                            </p>
                        @else
                            <p class="muted"> Sin coordenadas registradas</p>
                        @endif

                        {{-- CONTROL MANUAL --}}
                        <div class="control">
                            <div><span class="label">Estado de visita:</span></div>
                            <div class="line"></div>

                            <div style="margin-top:6px;"><span class="label">Observaciones:</span></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>

                    </div>
                @endforeach

                {{-- ================= FOOTER ================= --}}
                <div class="footer">
                    CCISUR · Sistema de Cobranza<br>
                    Documento generado el {{ now()->format('d/m/Y H:i') }}
                </div>

            </body>

            </html>
