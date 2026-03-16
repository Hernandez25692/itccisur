<x-app-layout>

    <div class="container">

        <h2 class="mb-4">Control de Empleados RRHH</h2>

        <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">
            Nuevo empleado
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>

                        <th>Nombre</th>
                        <th>Identidad</th>
                        <th>Cargo</th>
                        <th>Área</th>
                        <th>Fecha contratación</th>

                        <th>Acumulado</th>
                        <th>Tomado</th>
                        <th>Pendiente</th>

                        <th>Estado</th>
                        <th width="180">Acciones</th>

                    </tr>
                </thead>

                <tbody>

                    @foreach ($empleados as $empleado)
                        @php

                            $anio = date('Y');

                            $periodo = $empleado->periodosVacaciones->where('anio', $anio)->first();

                            $vacaciones = $periodo ? $periodo->dias_correspondientes : $empleado->vacacionesPorLey();

                            $acumulado = $periodo
                                ? $periodo->acumulado_anterior
                                : $empleado->vacaciones_acumuladas ?? 0;

                            $tomado = $periodo ? $periodo->total_tomado : 0;

                            $pendiente = $vacaciones + $acumulado - $tomado;

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

                            <td class="text-center text-success font-bold">
                                {{ number_format($pendiente, 2) }}
                            </td>

                            <td>

                                @if ($empleado->estado == 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif

                            </td>

                            <td>

                                <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        Activar / Inactivar
                                    </button>

                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        {{ $empleados->links() }}

    </div>

</x-app-layout>
