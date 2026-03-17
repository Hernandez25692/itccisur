<x-app-layout>

    <div class="container">

        <h2 class="text-2xl font-bold mb-4">
            Control de Vacaciones {{ $anio }}
        </h2>

        {{-- 🔥 DASHBOARD --}}
        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h6>Total Empleados</h6>
                    <h3>{{ $totalEmpleados }}</h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h6>Días Pendientes</h6>
                    <h3>{{ number_format($totalPendiente, 2) }}</h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 shadow-sm bg-danger text-white">
                    <h6>Críticos</h6>
                    <h3>{{ $criticos }}</h3>
                </div>
            </div>

        </div>

        {{-- 🔎 FILTROS --}}
        <form method="GET" class="card p-3 mb-4 shadow-sm">

            <div class="row">

                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar colaborador"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="anio" class="form-control" value="{{ request('anio', $anio) }}">
                </div>

                <div class="col-md-2">
                    <input type="text" name="area" class="form-control" placeholder="Área"
                        value="{{ request('area') }}">
                </div>

                <div class="col-md-2">
                    <select name="pendiente" class="form-control">
                        <option value="">Vacaciones</option>
                        <option value="con">Con saldo</option>
                        <option value="sin">Sin saldo</option>
                        <option value="critico">Crítico</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-dark w-100">Filtrar</button>
                </div>

            </div>

        </form>

        {{-- TABLA --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover text-sm">

                <thead class="table-dark">
                    <tr>
                        <th>Colaborador</th>
                        <th>Ingreso</th>
                        <th>Vac.</th>
                        <th>Acum.</th>

                        @for ($i = 1; $i <= 12; $i++)
                            <th>{{ date('M', mktime(0, 0, 0, $i, 1)) }}</th>
                        @endfor

                        <th>Permisos</th>
                        <th>Total</th>
                        <th>Pendiente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($empleados as $empleado)

                        @php
                            $periodo = $empleado->periodo_actual;
                            $vacaciones = $empleado->vacacionesPorLey();
                            $acumulado = $periodo ? $periodo->acumulado_anterior : 0;
                            $tomado = $periodo ? $periodo->total_tomado : 0;
                            $pendiente = $empleado->pendiente_calculado;

                            if ($pendiente <= 5) {
                                $color = 'text-success';
                            } elseif ($pendiente <= 15) {
                                $color = 'text-warning';
                            } else {
                                $color = 'text-danger fw-bold';
                            }
                        @endphp

                        <tr>

                            <td class="fw-bold">{{ $empleado->nombre_completo }}</td>

                            <td>{{ \Carbon\Carbon::parse($empleado->fecha_contratacion)->format('d-m-Y') }}</td>

                            <td>{{ number_format($vacaciones, 2) }}</td>
                            <td>{{ number_format($acumulado, 2) }}</td>

                            @for ($i = 1; $i <= 12; $i++)
                                @php
                                    $valor = $periodo
                                        ? $periodo->movimientos
                                            ->where('mes_referencia', $i)
                                            ->where('tipo_movimiento', '!=', 'permiso_especial')
                                            ->sum('dias_equivalentes')
                                        : 0;
                                @endphp

                                <td class="text-center">{{ number_format($valor, 2) }}</td>
                            @endfor

                            <td class="text-center">
                                {{ number_format(
                                    $periodo?->movimientos->where('tipo_movimiento', 'permiso_especial')->sum('dias_equivalentes') ?? 0,
                                    2,
                                ) }}
                            </td>

                            <td class="text-center fw-bold">{{ number_format($tomado, 2) }}</td>

                            <td class="text-center {{ $color }}">
                                {{ number_format($pendiente, 2) }}

                                @if ($pendiente > 15)
                                    <br>
                                    <span class="badge bg-danger">Urgente</span>
                                @endif
                            </td>

                            <td class="d-flex gap-1">

                                <a href="{{ route('vacaciones.crear', $empleado->id) }}"
                                    class="btn btn-primary btn-sm">
                                    +
                                </a>

                                <a href="{{ route('vacaciones.historial', $empleado->id) }}"
                                    class="btn btn-secondary btn-sm">
                                    👁
                                </a>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="20" class="text-center">
                                Sin registros
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>
