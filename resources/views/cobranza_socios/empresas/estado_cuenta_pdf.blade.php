<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - {{ $empresa->nombre_empresa }}</title>

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
        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 150px;
        }

        .header-right {
            text-align: right;
            font-size: 9pt;
            line-height: 1.4;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin: 10px 0;
            text-transform: uppercase;
        }

        /* ================= INFO EMPRESA ================= */
        .info-box {
            width: 100%;
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 140px;
        }

        /* ================= TABLA ================= */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 8px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .data-table th {
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .estado-pagado {
            font-weight: bold;
            color: #006400;
        }

        .estado-pendiente {
            font-weight: bold;
            color: #8B4513;
        }

        .estado-vencido {
            font-weight: bold;
            color: #8B0000;
        }

        /* ================= TOTALES ================= */
        .totales-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9pt;
        }

        .totales-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .totales-label {
            text-align: right;
            font-weight: bold;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: 15px;
            font-size: 8pt;
            text-align: center;
        }

        .firmas {
            width: 100%;
            margin-top: 35px;
        }

        .firma-box {
            width: 45%;
            text-align: center;
            display: inline-block;
        }

        .linea-firma {
            border-top: 1px solid #000;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <img src="{{ public_path('storage/logos/logop.png') }}" class="logo">
                </td>
                <td class="header-right">
                    <strong>CÁMARA DE COMERCIO E INDUSTRIAS DEL SUR</strong><br>
                    Ciudad Balcanes, Parque Industrial, Choluteca<br>
                    Tel: 3315-0844<br>
                    RTN: 06019008150622<br>
                    info@ccisur.org
                </td>
            </tr>
        </table>
    </div>

    <div class="header-title">
        ESTADO DE CUENTA
    </div>

    {{-- ================= INFO EMPRESA ================= --}}
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="label">Nombre de la Empresa:</td>
                <td>{{ $empresa->nombre_empresa }}</td>
            </tr>
            <tr>
                <td class="label">RTN:</td>
                <td>{{ $empresa->rtn_empresa }}</td>
            </tr>
            <tr>
                <td class="label">Tipo de Escritura:</td>
                <td>{{ $empresa->tipoEmpresa->nombre ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Tipo de Pago:</td>
                <td>{{ strtoupper($empresa->tipo_pago) }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Emisión:</td>
                <td>{{ now()->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- ================= TABLA DETALLE ================= --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>Período</th>
                <th>Año</th>
                <th>Mes</th>
                <th>Días</th>
                <th>Estado</th>
                <th>Monto (L)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filas as $f)
                <tr>
                    <td>{{ $f['periodo_texto'] }}</td>
                    <td class="text-center">{{ $f['anio'] }}</td>
                    <td class="text-center">{{ str_pad($f['mes'], 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="text-center">{{ $f['dias'] ?? '—' }}</td>
                    <td class="text-center estado-{{ strtolower($f['estado']) }}">
                        {{ strtoupper($f['estado']) }}
                    </td>
                    <td class="text-right">{{ number_format($f['monto'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= TOTALES ================= --}}
    <table class="totales-table">
        <tr>
            <td class="totales-label" width="80%">TOTAL ADEUDA (L)</td>
            <td class="text-right"><strong>{{ number_format($totalAdeuda, 2) }}</strong></td>
        </tr>
    </table>

    {{-- ================= FIRMAS ================= --}}
    <div class="firmas">
        <div class="firma-box">
            <div class="linea-firma"></div>
            Elaborado por
        </div>

        <div class="firma-box" style="float:right;">
            <div class="linea-firma"></div>
            Firma del Cliente
        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    <div class="footer">
        Documento generado automáticamente por el sistema CCISUR<br>
        Válido sin firma ni sello
    </div>

</body>

</html>
