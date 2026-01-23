<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - {{ $empresa->nombre_empresa }}</title>

    <style>
        /* Configuración carta vertical */
        @page {
            size: letter portrait;
            margin: 20mm 15mm;
        }

        body {
            font-family: 'Calibri', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.1;
            color: #1a365d;
            margin: 0;
            padding: 0;
            background: white;
        }

        /* Header mejorado */
        .header {
            width: 100%;
            border-bottom: 2.5pt solid #1e40af;
            padding-bottom: 10pt;
            margin-bottom: 12pt;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 160pt;
            vertical-align: middle;
        }

        .logo {
            max-width: 160pt;
            height: auto;
        }

        .title-cell {
            text-align: right;
            vertical-align: middle;
        }

        .title {
            font-size: 16pt;
            font-weight: 700;
            color: #1e40af;
            margin: 0 0 3pt 0;
            letter-spacing: -0.5pt;
        }

        .date {
            font-size: 9pt;
            color: #64748b;
            font-weight: 500;
            margin: 0;
        }

        /* Información empresa - diseño compacto */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4pt 15pt;
            margin: 0 0 10pt 0;
            padding: 8pt;
            background: #f8fafc;
            border-radius: 4pt;
            border-left: 3pt solid #d97706;
        }

        .info-item {
            display: flex;
            margin: 0;
            font-size: 9pt;
        }

        .info-label {
            font-weight: 600;
            color: #1e40af;
            min-width: 90pt;
            flex-shrink: 0;
        }

        .info-value {
            color: #2d3748;
            font-weight: 500;
        }

        /* Tabla mejorada */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8pt 0;
            font-size: 8.5pt;
        }

        .data-table thead {
            background: #1e40af;
            color: white;
        }

        .data-table th {
            padding: 6pt 4pt;
            font-weight: 600;
            text-align: center;
            border: 1pt solid #2d5a8c;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }

        .data-table td {
            padding: 5pt 4pt;
            border: 1pt solid #e2e8f0;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Estados con colores */
        .estado-pagado {
            color: #065f46;
            font-weight: 600;
        }

        .estado-pendiente {
            color: #92400e;
            font-weight: 600;
        }

        .estado-vencido {
            color: #991b1b;
            font-weight: 600;
        }

        /* Total destacado */
        .total-row td {
            font-weight: 700;
            background: linear-gradient(to right, #1e40af, #2d5a8c);
            color: white;
            padding: 7pt 4pt;
            border: 1pt solid #1e40af;
        }

        .total-row .text-right {
            font-size: 9pt;
        }

        /* Footer institucional */
        .footer {
            margin-top: 12pt;
            padding-top: 8pt;
            border-top: 1pt solid #cbd5e0;
            font-size: 8pt;
            color: #64748b;
            text-align: center;
            line-height: 1.3;
        }

        .institution-name {
            font-weight: 600;
            color: #1e40af;
        }

        .disclaimer {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 3pt;
        }

        /* Utilitarios para impresión */
        @media print {
            body {
                font-size: 10pt !important;
            }

            .header {
                page-break-after: avoid;
            }

            .data-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- ===================== HEADER ===================== --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('storage/logos/logop.png') }}" class="logo" alt="CCISUR">
            </td>
            <td class="title-cell">
                <h2 class="title">Estado de Cuenta</h2>
                <p class="date">{{ now()->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    {{-- ===================== INFO EMPRESA ===================== --}}
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Empresa:</span>
            <span class="info-value">{{ $empresa->nombre_empresa }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">RTN:</span>
            <span class="info-value">{{ $empresa->rtn_empresa }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Tipo Escritura:</span>
            <span class="info-value">{{ $empresa->tipoEmpresa->nombre ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Tipo de Pago:</span>
            <span class="info-value">{{ strtoupper($empresa->tipo_pago) }}</span>
        </div>
    </div>

    {{-- ===================== TABLA ===================== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="14%">Tipo Escritura</th>
                <th width="20%">Nombre Social</th>
                <th width="12%">Período</th>
                <th width="6%">Días</th>
                <th width="6%">Año</th>
                <th width="6%">Mes</th>
                <th width="10%">Estado</th>
                <th width="16%">Adeuda (L)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filas as $f)
                <tr>
                    <td class="text-left">
                        {{ $empresa->tipoEmpresa->nombre ?? '—' }}
                    </td>

                    <td class="text-left">
                        {{ $empresa->nombre_empresa }}
                    </td>

                    <td class="text-left">
                        {{ $f['periodo_texto'] }}
                    </td>

                    <td class="text-center">
                        {{ $f['dias'] ?? '—' }}
                    </td>

                    <td class="text-center">
                        {{ $f['anio'] }}
                    </td>

                    <td class="text-center">
                        {{ str_pad($f['mes'], 2, '0', STR_PAD_LEFT) }}
                    </td>

                    <td class="text-center estado-{{ strtolower($f['estado']) }}">
                        {{ strtoupper($f['estado']) }}
                    </td>

                    <td class="text-right">
                        {{ number_format($f['monto'], 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right" style="color: black; font-weight: bold;">TOTAL ADEUDA</td>
                <td class="text-right" style="color: black; font-weight: bold;">
                    L. {{ number_format($totalAdeuda, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- ===================== FOOTER ===================== --}}
    <div class="footer">
        <div class="institution-name">Cámara de Comercio e Industrias del Sur (CCISUR)</div>
        <div class="disclaimer">
            Documento generado automáticamente – válido sin firma
        </div>
    </div>

</body>

</html>
