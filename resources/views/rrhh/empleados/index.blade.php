<x-app-layout>

    <div class="container">

        <h2 class="mb-4">Control de Empleados RRHH</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('empleados.create') }}" class="btn btn-primary">
                + Nuevo empleado
            </a>
        </div>

        {{-- ALERTA --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTROS --}}
        <form method="GET" class="card p-3 mb-4 shadow-sm">

            <div class="row">

            {{-- BUSCADOR --}}
            <div class="col-md-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar nombre o identidad"
                value="{{ request('search') }}">
            </div>

            {{-- ESTADO --}}
            <div class="col-md-2 mb-2">
                <select name="estado" class="form-control">
                <option value="">Estado</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo
                </option>
                </select>
            </div>

            {{-- AREA --}}
            <div class="col-md-2 mb-2">
                <input type="text" name="area" class="form-control" placeholder="Área"
                value="{{ request('area') }}">
            </div>

            {{-- PENDIENTES --}}
            <div class="col-md-2 mb-2">
                <select name="pendiente" class="form-control">
                <option value="">Vacaciones</option>
                <option value="con" {{ request('pendiente') == 'con' ? 'selected' : '' }}>
                    Con saldo
                </option>
                <option value="sin" {{ request('pendiente') == 'sin' ? 'selected' : '' }}>
                    Sin saldo
                </option>
                <option value="critico" {{ request('pendiente') == 'critico' ? 'selected' : '' }}>
                    Crítico (>15 días)
                </option>
                </select>
            </div>

            {{-- BOTONES --}}
            <div class="col-md-3 mb-2 d-flex gap-2">
                <button class="btn btn-dark flex-grow-1">
                Filtrar
                </button>
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                Limpiar
                </a>
            </div>

            </div>

        </form>

        {{-- TABLA --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Identidad</th>
                        <th>Cargo</th>
                        <th>Área</th>
                        <th>Contratación</th>

                        <th class="text-center">Acumulado</th>
                        <th class="text-center">Tomado</th>
                        <th class="text-center">Pendiente</th>

                        <th>Estado</th>
                        <th width="200">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($empleados as $empleado)
                        @php
                            $anio = date('Y');

                            $periodo = $empleado->periodosVacaciones->where('anio', $anio)->first();

                            $vacaciones = $periodo ? $periodo->dias_correspondientes : $empleado->vacacionesPorLey();

                            $acumulado = $periodo
                                ? $periodo->acumulado_anterior
                                : $empleado->vacaciones_acumuladas ?? 0;

                            $tomado = $periodo ? $periodo->total_tomado : 0;

                            $pendiente = $vacaciones + $acumulado - $tomado;

                            // COLORES INTELIGENTES
                            if ($pendiente <= 5) {
                                $color = 'text-success';
                            } elseif ($pendiente <= 15) {
                                $color = 'text-warning';
                            } else {
                                $color = 'text-danger fw-bold';
                            }

                        @endphp

                        <tr>

                            <td>{{ $empleado->nombre_completo }}</td>

                            <td>{{ $empleado->identidad }}</td>

                            <td>{{ $empleado->cargo }}</td>

                            <td>{{ $empleado->area }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($empleado->fecha_contratacion)->format('d-m-Y') }}
                            </td>

                            <td class="text-center">
                                {{ number_format($acumulado, 2) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($tomado, 2) }}
                            </td>

                            <td class="text-center {{ $color }}">
                                {{ number_format($pendiente, 2) }}

                                @if ($pendiente > 15)
                                    <br>
                                    <small class="badge bg-danger">Debe salir</small>
                                @endif
                            </td>

                            <td>
                                @if ($empleado->estado == 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route('empleados.edit', $empleado->id) }}"
                                        class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">
                                            Estado
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="text-center">
                                No hay empleados registrados
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINACION --}}
        <div class="mt-3">
            {{ $empleados->withQueryString()->links() }}
        </div>

    </div>

</x-app-layout>
