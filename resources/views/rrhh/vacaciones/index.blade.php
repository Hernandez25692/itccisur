<x-app-layout>

    <div class="container">

        <h2 class="text-2xl font-bold mb-4">
            Control de Vacaciones {{ $anio }}
        </h2>

        <div class="overflow-x-auto">

            <table class="table table-bordered table-striped text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th>Colaborador</th>
                        <th>Contratación</th>

                        <th>Vacaciones</th>
                        <th>Acumulado</th>

                        <th>Ene</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Abr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Ago</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dic</th>

                        <th>Permisos</th>
                        <th>Total</th>
                        <th>Pendiente</th>

                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($empleados as $empleado)
                        @php

                            $periodo = $empleado->periodosVacaciones->first();

                            $vacaciones = $empleado->vacacionesPorLey();

                            $acumulado = $periodo
                                ? $periodo->acumulado_anterior
                                : $empleado->vacaciones_acumuladas ?? 0;

                            $totalTomado = $periodo ? $periodo->total_tomado : 0;

                            $permisos = 0;

                            if ($periodo) {
                                $permisos = $periodo->movimientos
                                    ->where('tipo_movimiento', 'permiso_especial')
                                    ->sum('dias_equivalentes');
                            }

                            $pendiente = $vacaciones + $acumulado - $totalTomado;

                        @endphp

                        <tr>

                            <td class="font-semibold">
                                {{ $empleado->nombre_completo }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($empleado->fecha_contratacion)->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ number_format($vacaciones, 2) }}
                            </td>

                            <td>
                                {{ number_format($acumulado, 2) }}
                            </td>

                            @for ($i = 1; $i <= 12; $i++)
                                @php

                                    $valorMes = 0;

                                    if ($periodo) {
                                        $valorMes = $periodo->movimientos
                                            ->where('mes_referencia', $i)
                                            ->where('tipo_movimiento', '!=', 'permiso_especial')
                                            ->sum('dias_equivalentes');
                                    }

                                @endphp

                                <td class="text-center">
                                    {{ number_format($valorMes, 2) }}
                                </td>
                            @endfor

                            <td class="text-center">
                                {{ number_format(
                                    $periodo?->movimientos->where('tipo_movimiento', 'permiso_especial')->sum('dias_equivalentes') ?? 0,
                                    2,
                                ) }}
                            </td>

                            <td class="text-center font-semibold">
                                {{ number_format($totalTomado, 2) }}
                            </td>

                            <td class="text-center text-green-700 font-bold">
                                {{ number_format($pendiente, 2) }}
                            </td>

                            <td class="flex gap-2">

                                <a href="{{ route('vacaciones.crear', $empleado->id) }}"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Registrar
                                </a>

                                <a href="{{ route('vacaciones.historial', $empleado->id) }}"
                                    class="bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                    Historial
                                </a>
                                

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>
