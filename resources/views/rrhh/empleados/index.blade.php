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
            --radius-md: 10px;
            --radius-lg: 14px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Título principal */
        h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 2rem !important;
            position: relative;
            padding-bottom: 0.75rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
            border-radius: 2px;
        }

        /* Botón Nuevo empleado */
        .btn-primary {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            border: none;
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(197, 160, 73, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--gold-dark), var(--gold-primary));
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(197, 160, 73, 0.4);
        }

        /* Filtros - Card */
        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        /* Inputs y selects */
        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.15);
            outline: none;
        }

        /* Botón Filtrar */
        .btn-dark {
            background: var(--blue-medium);
            border: none;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-dark:hover {
            background: var(--blue-deep);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(30, 41, 59, 0.2);
        }

        /* Botón Limpiar */
        .btn-secondary {
            background: white;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.6rem 1rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--bg-light);
            border-color: var(--text-muted);
            color: var(--text-dark);
        }

        /* Tabla */
        .table {
            width: 100%;
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--blue-deep), var(--blue-medium));
        }

        .table thead th {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
            border: none;
            position: relative;
        }

        .table thead th:first-child {
            border-top-left-radius: var(--radius-lg);
        }

        .table thead th:last-child {
            border-top-right-radius: var(--radius-lg);
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background-color: rgba(197, 160, 73, 0.05);
            transform: scale(1.002);
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        /* Estados de pendiente */
        .text-success {
            color: #10b981 !important;
            font-weight: 600;
        }

        .text-warning {
            color: var(--gold-primary) !important;
            font-weight: 600;
        }

        .text-danger {
            color: #ef4444 !important;
            font-weight: 700;
        }

        .badge.bg-danger {
            background: #ef4444 !important;
            color: white;
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            margin-top: 4px;
            display: inline-block;
        }

        /* Badges de estado activo/inactivo */
        .badge.bg-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
        }

        /* Botones de acciones */
        .btn-warning {
            background: var(--gold-primary);
            border: none;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(197, 160, 73, 0.2);
        }

        .btn-warning:hover {
            background: var(--gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(197, 160, 73, 0.3);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Alerta de éxito */
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success::before {
            content: '✓';
            display: inline-block;
            width: 24px;
            height: 24px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: bold;
        }

        /* Paginación */
        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 0.25rem;
        }

        .pagination .page-item .page-link {
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .pagination .page-item.active .page-link {
            background: var(--gold-primary);
            border-color: var(--gold-primary);
            color: white;
        }

        .pagination .page-item .page-link:hover {
            background: var(--gold-light);
            border-color: var(--gold-primary);
            color: var(--blue-deep);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                padding: 1rem;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border: none;
            }

            .table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--blue-deep);
                margin-right: 1rem;
            }

            .d-flex.gap-1 {
                flex-wrap: wrap;
            }
        }

        /* Botón vacaciones */
        .btn-info {
            background: var(--blue-medium);
            border: none;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-info:hover {
            background: var(--blue-deep);
            color: var(--gold-light);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #b91c1c;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .text-sub {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .legend {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 1rem;
        }
    </style>

    @php
        $critico = config('rrhh.vacaciones.saldo_critico');
    @endphp

    <div class="container">
        <h2 class="mb-4">Control de Empleados RRHH</h2>

        <div class="d-flex justify-content-between mb-3" style="display:flex; gap:.5rem; flex-wrap:wrap;">
            <a href="{{ route('empleados.create') }}" class="btn btn-primary">
                + Nuevo empleado
            </a>
            <a href="{{ route('vacaciones.index') }}" class="btn btn-info" style="align-self:center;">
                Control de vacaciones
            </a>
        </div>

        {{-- ALERTA --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- FILTROS --}}
        <form method="GET" class="bg-[#1e293b] p-4 rounded shadow mb-6 flex flex-wrap items-center gap-4">

            {{-- BUSCADOR --}}
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Buscar nombre o identidad"
                    value="{{ request('search') }}"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
            </div>

            {{-- ESTADO --}}
            <div class="flex-1 min-w-[120px]">
                <select name="estado"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                    <option value="">Estado</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            {{-- AREA --}}
            <div class="flex-1 min-w-[150px]">
                <select name="area"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                    <option value="">Área</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                    @endforeach
                </select>
            </div>

            {{-- PENDIENTES --}}
            <div class="flex-1 min-w-[150px]">
                <select name="pendiente"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#C5A049] text-[#0f172a]">
                    <option value="">Vacaciones</option>
                    <option value="con" {{ request('pendiente') == 'con' ? 'selected' : '' }}>Con saldo</option>
                    <option value="sin" {{ request('pendiente') == 'sin' ? 'selected' : '' }}>Sin saldo</option>
                    <option value="critico" {{ request('pendiente') == 'critico' ? 'selected' : '' }}>Crítico (&gt;{{ $critico }}
                        días)</option>
                </select>
            </div>

            {{-- BOTONES --}}
            <div class="flex gap-2 min-w-[220px]">
                <button
                    class="flex-1 bg-[#0f172a] hover:bg-[#1e293b] text-[#C5A049] font-semibold px-4 py-2 rounded shadow">
                    Filtrar
                </button>
                <a href="{{ route('empleados.index') }}"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded shadow text-center">
                    Limpiar
                </a>
            </div>

        </form>

        {{-- TABLA --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Identidad</th>
                        <th>Cargo / Área</th>
                        <th>Contratación</th>
                        <th class="text-center" title="Días por ley del periodo laboral actual">Días ley</th>
                        <th class="text-center" title="Vacaciones acumuladas de periodos anteriores que aún no se toman">Acum.</th>
                        <th class="text-center" title="Días tomados en el periodo laboral actual">Tomado</th>
                        <th class="text-center" title="Saldo disponible hoy">Saldo</th>
                        <th>Estado</th>
                        <th width="220">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($empleados as $empleado)
                        @php
                            $estado = $estados[$empleado->id];
                            $saldo = $estado->saldo;

                            // COLORES INTELIGENTES
                            if ($saldo <= 5) {
                                $color = 'text-success';
                            } elseif ($saldo <= $critico) {
                                $color = 'text-warning';
                            } else {
                                $color = 'text-danger fw-bold';
                            }
                        @endphp
                        <tr>
                            <td data-label="Nombre">{{ $empleado->nombre_completo }}</td>
                            <td data-label="Identidad">{{ $empleado->identidad }}</td>
                            <td data-label="Cargo / Área">
                                {{ $empleado->cargo }}
                                <span class="text-sub">{{ $empleado->area }}</span>
                            </td>
                            <td data-label="Contratación">
                                {{ $empleado->fecha_contratacion->format('d-m-Y') }}
                                <span class="text-sub">{{ $empleado->aniosServicio() }} año(s)</span>
                            </td>
                            <td data-label="Días ley" class="text-center">{{ \App\Services\Rrhh\VacacionesService::numero($estado->diasPorLeyActual()) }}</td>
                            <td data-label="Acum." class="text-center">{{ number_format($estado->acumuladoRestante(), 2) }}</td>
                            <td data-label="Tomado" class="text-center">{{ number_format($estado->tomadoPeriodoActual(), 2) }}</td>
                            <td data-label="Saldo" class="text-center {{ $color }}">
                                {{ number_format($saldo, 2) }}
                                @if ($saldo < 0)
                                    <span class="text-sub">adelantado</span>
                                @endif
                                @if ($saldo > $critico)
                                    <br>
                                    <small class="badge bg-danger">Debe salir</small>
                                @endif
                            </td>
                            <td data-label="Estado">
                                @if ($empleado->estado == 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                    @if ($empleado->fecha_salida)
                                        <span class="text-sub">Salida: {{ $empleado->fecha_salida->format('d-m-Y') }}</span>
                                    @endif
                                @endif
                            </td>
                            <td data-label="Acciones">
                                <div class="d-flex gap-1" style="display:flex; gap:.35rem; flex-wrap:wrap;">
                                    <a href="{{ route('empleados.edit', $empleado) }}"
                                        class="btn btn-warning btn-sm">
                                        Editar
                                    </a>
                                    <a href="{{ route('vacaciones.historial', $empleado) }}"
                                        class="btn btn-info btn-sm">
                                        Vacaciones
                                    </a>
                                    <button type="button"
                                        class="btn {{ $empleado->estado === 'activo' ? 'btn-danger' : 'btn-success' }} btn-sm js-estado-btn"
                                        data-action="{{ route('empleados.estado', $empleado) }}"
                                        data-name="{{ $empleado->nombre_completo }}"
                                        data-accion="{{ $empleado->estado === 'activo' ? 'desactivar' : 'activar' }}">
                                        {{ $empleado->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                No hay empleados registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="legend">
            Saldo = acumulado histórico + días por ley acreditados − vacaciones tomadas o programadas − días vencidos.
            Los permisos especiales no descuentan.
        </p>

        {{-- PAGINACION --}}
        <div class="mt-3">
            {{ $empleados->links() }}
        </div>

        {{-- Modal para cambiar estado --}}
        <div id="estadoModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl overflow-hidden">
                <div class="flex items-start justify-between gap-4 bg-sky-50 border-b border-sky-200 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-sky-700">Cambiar estado</h3>
                        <p class="text-sm text-sky-600 mt-1">¿Deseas <span id="estadoModalAccion">cambiar el estado de</span> <span id="estadoModalName" class="font-semibold text-sky-700"></span>?</p>
                        <p id="estadoModalNota" class="text-sm text-slate-500 mt-2"></p>
                    </div>
                    <button type="button" id="estadoModalClose" class="text-slate-500 hover:text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-6">
                    <form id="estadoModalForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <div class="flex flex-col sm:flex-row items-center justify-end gap-2">
                            <button type="button" id="estadoModalCancel"
                                class="w-full sm:w-auto px-4 py-2 rounded-md border border-gray-300 bg-white text-slate-700 hover:bg-gray-50 transition">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 rounded-md bg-rose-600 text-white hover:bg-rose-700 transition">
                                Cambiar estado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('estadoModal');
                const form = document.getElementById('estadoModalForm');
                const nameLabel = document.getElementById('estadoModalName');
                const accionLabel = document.getElementById('estadoModalAccion');
                const nota = document.getElementById('estadoModalNota');
                const cancelBtn = document.getElementById('estadoModalCancel');
                const closeBtn = document.getElementById('estadoModalClose');

                document.querySelectorAll('.js-estado-btn').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const desactivar = btn.dataset.accion === 'desactivar';
                        form.action = btn.dataset.action;
                        nameLabel.textContent = btn.dataset.name || 'este empleado';
                        accionLabel.textContent = desactivar ? 'desactivar a' : 'reactivar a';
                        nota.textContent = desactivar
                            ? 'Se registrará la fecha de salida de hoy y dejará de recibir acreditaciones de vacaciones.'
                            : 'Volverá a recibir acreditaciones por aniversario.';
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                const closeModal = () => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                };

                cancelBtn.addEventListener('click', closeModal);
                closeBtn.addEventListener('click', closeModal);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            });
        </script>
    </div>
</x-app-layout>
