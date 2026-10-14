<x-app-layout>
    <style>
        :root {
            --blue-deep: #0f172a;
            --blue-medium: #1e293b;
            --blue-light: #334155;
            --gold-primary: #C5A049;
            --gold-light: #d4b168;
            --gold-dark: #a8843a;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 4px rgba(15, 23, 42, 0.06);
            --shadow-md: 0 4px 6px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 10px 15px rgba(15, 23, 42, 0.1);
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        .container-custom {
            max-width: 1600px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
        }

        /* Título principal */
        h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 1.5rem !important;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 70px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
            border-radius: 2px;
        }

        /* Dashboard cards */
        .dashboard-card {
            background: var(--blue-medium);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            padding: 1rem !important;
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .dashboard-card.critical {
            background: #dc2626;
        }

        .dashboard-card h6 {
            color: var(--gold-primary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .dashboard-card.critical h6 {
            color: white;
        }

        .dashboard-card h3 {
            color: white;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        /* Filtros - Card */
        .filters-card {
            background: var(--blue-medium);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.3s ease;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .filters-card:hover {
            box-shadow: var(--shadow-md);
        }

        /* Inputs y selects */
        .filter-input,
        .filter-select {
            width: 100%;
            padding: 0.4rem 0.6rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 0.8rem;
            transition: all 0.3s ease;
            background-color: white;
            color: var(--text-dark);
        }

        .filter-input:focus,
        .filter-select:focus {
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 2px rgba(197, 160, 73, 0.15);
            outline: none;
        }

        .filter-input::placeholder {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Botón Filtrar */
        .btn-filter {
            width: 100%;
            background: var(--gold-primary);
            border: none;
            color: var(--blue-deep);
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius-md);
            font-size: 0.8rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(197, 160, 73, 0.3);
            cursor: pointer;
        }

        .btn-filter:hover {
            background: var(--gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(197, 160, 73, 0.4);
        }

        /* Tabla - CON SCROLL Y COLUMNAS FIJAS */
        .table-container {
            width: 100%;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow-x: auto;
            overflow-y: visible;
            max-height: 800px;
        }

        .custom-table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            table-layout: auto;
            min-width: 1400px;
        }

        .custom-table thead {
            background: linear-gradient(135deg, var(--blue-deep), var(--blue-medium));
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .custom-table thead th {
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 0.8rem 0.5rem;
            border: none;
            white-space: nowrap;
            text-align: center;
        }

        /* Primeras 4 columnas fijas */
        .custom-table thead th:nth-child(1),
        .custom-table thead th:nth-child(2),
        .custom-table thead th:nth-child(3),
        .custom-table thead th:nth-child(4) {
            position: sticky;
            background: linear-gradient(135deg, var(--blue-deep), var(--blue-medium));
            z-index: 50;
        }

        .custom-table thead th:nth-child(1) {
            left: 0;
            border-top-left-radius: var(--radius-lg);
            text-align: left;
            padding-left: 0.8rem;
            min-width: 140px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
        }

        .custom-table thead th:nth-child(2) {
            left: 140px;
            min-width: 90px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
        }

        .custom-table thead th:nth-child(3) {
            left: 230px;
            min-width: 70px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
        }

        .custom-table thead th:nth-child(4) {
            left: 300px;
            min-width: 70px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
        }

        /* Meses - ancho aumentado */
        .custom-table thead th:nth-child(n+5):nth-child(-n+16) {
            min-width: 50px;
        }

        .custom-table thead th:nth-child(17) {
            min-width: 80px;
        }

        .custom-table thead th:nth-child(18) {
            min-width: 70px;
        }

        .custom-table thead th:nth-child(19) {
            min-width: 80px;
        }

        .custom-table thead th:last-child {
            border-top-right-radius: var(--radius-lg);
            min-width: 120px;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-table tbody tr:hover {
            background-color: rgba(197, 160, 73, 0.05);
        }

        .custom-table tbody td {
            padding: 0.7rem 0.5rem;
            vertical-align: middle;
            color: var(--text-dark);
            font-size: 0.8rem;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Primeras 4 columnas fijas */
        .custom-table tbody td:nth-child(1),
        .custom-table tbody td:nth-child(2),
        .custom-table tbody td:nth-child(3),
        .custom-table tbody td:nth-child(4) {
            position: sticky;
            background: white !important;
            z-index: 50;
        }

        .custom-table tbody tr:hover td:nth-child(1),
        .custom-table tbody tr:hover td:nth-child(2),
        .custom-table tbody tr:hover td:nth-child(3),
        .custom-table tbody tr:hover td:nth-child(4) {
            background-color: #f0ede5 !important;
        }

        .custom-table tbody td:nth-child(1) {
            left: 0;
            padding-left: 0.8rem;
            font-weight: 600;
            text-align: left;
            min-width: 140px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
        }

        .custom-table tbody td:nth-child(2) {
            left: 140px;
            text-align: center;
            min-width: 90px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
        }

        .custom-table tbody td:nth-child(3) {
            left: 230px;
            text-align: center;
            min-width: 70px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
        }

        .custom-table tbody td:nth-child(4) {
            left: 300px;
            text-align: center;
            min-width: 70px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
        }

        .custom-table tbody td:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)):not(:nth-child(4)) {
            text-align: center;
        }

        /* Colores para pendiente */
        .text-green-custom {
            color: #10b981 !important;
            font-weight: 600;
        }

        .text-yellow-custom {
            color: var(--gold-primary) !important;
            font-weight: 600;
        }

        .text-red-custom {
            color: #ef4444 !important;
            font-weight: 700;
        }

        /* Badge urgente */
        .badge-urgent {
            background: #ef4444 !important;
            color: white;
            font-size: 0.5rem;
            padding: 0.1rem 0.25rem;
            border-radius: 20px;
            margin-top: 2px;
            display: inline-block;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1;
        }

        /* Botones de acciones */
        .btn-action-add {
            background: var(--gold-primary);
            border: none;
            color: var(--blue-deep);
            padding: 0.3rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(197, 160, 73, 0.2);
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
            margin: 0 2px;
            min-width: 32px;
            text-align: center;
            line-height: 1.2;
        }

        .btn-action-add:hover {
            background: var(--gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(197, 160, 73, 0.3);
            color: white;
        }

        .btn-action-view {
            background: #64748b;
            border: none;
            color: white;
            padding: 0.3rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2);
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
            margin: 0 2px;
            min-width: 40px;
            text-align: center;
            line-height: 1.2;
        }

        .btn-action-view:hover {
            background: #475569;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(100, 116, 139, 0.3);
            color: white;
        }

        /* Grid responsive */
        .grid-custom {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .grid-custom {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Filtros grid */
        .filters-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        @media (min-width: 768px) {
            .filters-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        /* Responsive table para móviles */
        @media (max-width: 1200px) {
            .custom-table {
                table-layout: auto;
                min-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .container-custom {
                padding: 1rem;
            }

            h2 {
                font-size: 1.6rem;
            }

            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                padding: 0.8rem;
            }

            .custom-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.3rem 0;
                border: none;
                white-space: normal;
                font-size: 0.8rem;
                text-align: right;
            }

            .custom-table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--blue-deep);
                margin-right: 1rem;
                min-width: 100px;
                text-align: left;
            }

            .custom-table tbody td:first-child {
                font-weight: 700;
                color: var(--blue-deep);
            }

            .btn-action-add,
            .btn-action-view {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
                min-width: 50px;
            }
        }

        .custom-table thead th:nth-child(20) {
            min-width: 90px;
        }

        .dashboard-card.warning {
            background: #b45309;
        }

        .dashboard-card.warning h6 {
            color: white;
        }

        .notice {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            border-radius: var(--radius-lg);
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }

        .notice.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #047857;
        }

        .notice.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #b91c1c;
        }

        .notice.warning {
            background: #fef3c7;
            border: 1px solid rgba(180, 83, 9, 0.3);
            color: #78350f;
        }

        .btn-process {
            background: var(--blue-deep);
            color: var(--gold-light);
            border: none;
            border-radius: var(--radius-md);
            padding: 0.45rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-process:hover {
            background: var(--blue-medium);
        }

        .btn-clear {
            display: block;
            width: 100%;
            text-align: center;
            background: #64748b;
            color: white;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius-md);
            font-size: 0.8rem;
        }

        .header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .text-sub {
            display: block;
            font-size: 0.65rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .legend {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.9rem;
            line-height: 1.6;
        }
    </style>

    @php
        $critico = config('rrhh.vacaciones.saldo_critico');
        $diasAlerta = config('rrhh.vacaciones.alerta_vencimiento_dias');
        $mesesES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    @endphp

    <div class="container-custom">
        {{-- Título --}}
        <div class="header-row">
            <h2>Control de Vacaciones {{ $anio }}</h2>

            <form method="POST" action="{{ route('vacaciones.procesar') }}">
                @csrf
                <button type="submit" class="btn-process"
                    title="Acredita los días por aniversario pendientes y recalcula los saldos. Se puede ejecutar varias veces sin duplicar.">
                    Procesar acreditaciones
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="notice success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="notice error">{{ session('error') }}</div>
        @endif

        @if ($pendientesAcreditar > 0)
            <div class="notice warning">
                <span>
                    Hay <strong>{{ $pendientesAcreditar }}</strong> empleado(s) con aniversario laboral sin acreditar o
                    con periodos del esquema anterior. Procese las acreditaciones para que los saldos queden al día.
                </span>
                <form method="POST" action="{{ route('vacaciones.procesar') }}">
                    @csrf
                    <button type="submit" class="btn-process">Procesar ahora</button>
                </form>
            </div>
        @endif

        {{-- DASHBOARD --}}
        <div class="grid-custom mb-6">
            <div class="dashboard-card text-center">
                <h6>Total Empleados</h6>
                <h3>{{ $totalEmpleados }}</h3>
            </div>
            <div class="dashboard-card text-center">
                <h6>Días Pendientes</h6>
                <h3>{{ number_format($totalPendiente, 2) }}</h3>
            </div>
            <div class="dashboard-card critical text-center">
                <h6>Críticos (&gt;{{ $critico }} días)</h6>
                <h3>{{ $criticos }}</h3>
            </div>
            <div class="dashboard-card warning text-center">
                <h6>Vencen en {{ $diasAlerta }} días</h6>
                <h3>{{ $porVencer }}</h3>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="filters-card">
            <form method="GET" class="filters-grid">
                <div>
                    <input type="text" name="search" placeholder="Buscar colaborador" value="{{ request('search') }}"
                        class="filter-input">
                </div>

                <div>
                    <input type="number" name="anio" min="2000" max="2100" value="{{ $anio }}"
                        class="filter-input" title="Año de los meses mostrados">
                </div>

                <div>
                    <select name="area" class="filter-select">
                        <option value="">Área</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="pendiente" class="filter-select">
                        <option value="">Vacaciones</option>
                        <option value="con" {{ request('pendiente') == 'con' ? 'selected' : '' }}>Con saldo</option>
                        <option value="sin" {{ request('pendiente') == 'sin' ? 'selected' : '' }}>Sin saldo</option>
                        <option value="critico" {{ request('pendiente') == 'critico' ? 'selected' : '' }}>Crítico
                        </option>
                        <option value="por_vencer" {{ request('pendiente') == 'por_vencer' ? 'selected' : '' }}>
                            Por vencer
                        </option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn-filter">
                        Filtrar
                    </button>
                </div>

                <div>
                    <a href="{{ route('vacaciones.index') }}" class="btn-clear">Limpiar</a>
                </div>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th>Ingreso</th>
                        <th title="Días por ley del periodo laboral actual">Ley</th>
                        <th title="Vacaciones acumuladas (histórico) pendientes de tomar">Acum.</th>
                        @for ($i = 1; $i <= 12; $i++)
                            <th title="Días descontados en {{ $mesesES[$i - 1] }} {{ $anio }}">{{ $mesesES[$i - 1] }}</th>
                        @endfor
                        <th title="Permisos especiales del año (no descuentan)">Permisos</th>
                        <th title="Días descontados en {{ $anio }}">Total</th>
                        <th title="Saldo disponible hoy">Saldo</th>
                        <th title="Próximo vencimiento de días no tomados">Vence</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($filas as $fila)
                        @php
                            $empleado = $fila['empleado'];
                            $estado = $fila['estado'];
                            $saldo = $estado->saldo;
                            $proximo = $estado->proximoVencimiento;
                            $venceProntoDias = $proximo ? (int) $hoy->diffInDays($proximo['fecha']) : null;

                            if ($saldo <= 5) {
                                $color = 'text-green-custom';
                            } elseif ($saldo <= $critico) {
                                $color = 'text-yellow-custom';
                            } else {
                                $color = 'text-red-custom';
                            }
                        @endphp
                        <tr>
                            <td data-label="Colaborador" class="font-semibold" title="{{ $empleado->nombre_completo }}">
                                {{ $empleado->nombre_completo }}</td>
                            <td data-label="Ingreso">{{ $empleado->fecha_contratacion->format('d-m-Y') }}</td>
                            <td data-label="Ley">{{ \App\Services\Rrhh\VacacionesService::numero($estado->diasPorLeyActual()) }}</td>
                            <td data-label="Acum.">
                                {{ $estado->acumuladoRestante() > 0 ? number_format($estado->acumuladoRestante(), 1) : '-' }}
                            </td>
                            @for ($i = 1; $i <= 12; $i++)
                                <td data-label="{{ $mesesES[$i - 1] }}">
                                    {{ $fila['meses'][$i] > 0 ? number_format($fila['meses'][$i], 1) : '-' }}</td>
                            @endfor
                            <td data-label="Permisos">
                                {{ $fila['permisos'] > 0 ? number_format($fila['permisos'], 1) : '-' }}
                            </td>
                            <td data-label="Total" class="font-bold">{{ number_format($fila['tomado_anio'], 1) }}</td>
                            <td data-label="Saldo" class="{{ $color }}" title="{{ \App\Services\Rrhh\VacacionesService::formatoDias($saldo) }}">
                                {{ number_format($saldo, 1) }}
                                @if ($saldo < 0)
                                    <span class="badge-urgent">Adelanto</span>
                                @elseif ($saldo > $critico)
                                    <span class="badge-urgent">Descanso</span>
                                @endif
                            </td>
                            <td data-label="Vence">
                                @if ($proximo)
                                    {{ $proximo['fecha']->format('d-m-Y') }}
                                    <span class="text-sub {{ $venceProntoDias <= $diasAlerta ? 'text-red-custom' : '' }}">
                                        {{ number_format($proximo['dias'], 1) }} d
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Acciones">
                                <div style="display: flex; gap: 0.2rem; justify-content: center; align-items: center;">
                                    <a href="{{ route('vacaciones.crear', $empleado) }}" class="btn-action-add"
                                        title="Registrar vacaciones o permiso">
                                        +
                                    </a>
                                    <a href="{{ route('vacaciones.historial', $empleado) }}"
                                        class="btn-action-view" title="Historial y estado de cuenta">
                                        Ver
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="21" class="text-center py-4 text-gray-500">
                                Sin registros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="legend">
            <strong>Saldo</strong>: días disponibles hoy (acumulado histórico + días por ley acreditados − vacaciones
            tomadas o programadas − días vencidos). Un saldo negativo es un adelanto que se compensa con la próxima
            acreditación.<br>
            Los días por ley se acreditan en cada aniversario laboral. Desde el
            {{ app(\App\Services\Rrhh\VacacionesService::class)->inicioSinAcumulacion()->format('d-m-Y') }} no se acumulan: vencen al
            siguiente aniversario, y las acumuladas históricas vencen el
            {{ app(\App\Services\Rrhh\VacacionesService::class)->vencimientoAcumuladoHistorico()->format('d-m-Y') }}.
            Los domingos no se descuentan; 8 horas = 1 día.
        </p>
    </div>
</x-app-layout>
