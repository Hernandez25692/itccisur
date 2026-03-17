<x-app-layout>

    <div class="container mx-auto">

        <h2 class="text-2xl font-bold mb-6">

            Historial de Vacaciones
            <br>

            <span class="text-blue-700">
                {{ $empleado->nombre_completo }}
            </span>

        </h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">

            <table class="min-w-full bg-white border text-sm">

                <thead class="bg-gray-200">

                    <tr>

                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2">Periodo</th>
                        <th class="px-3 py-2 text-center">Horas</th>
                        <th class="px-3 py-2 text-center">Días</th>
                        <th class="px-3 py-2">Motivo</th>
                        <th class="px-3 py-2">Comprobante</th>
                        <th class="px-3 py-2 text-center">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($movimientos as $mov)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-3 py-2 font-semibold">

                                @if ($mov->tipo_movimiento == 'vacacion_dias')
                                    <span class="text-green-700">
                                        Vacación (días)
                                    </span>
                                @endif

                                @if ($mov->tipo_movimiento == 'vacacion_horas')
                                    <span class="text-blue-700">
                                        Vacación (horas)
                                    </span>
                                @endif

                                @if ($mov->tipo_movimiento == 'permiso_especial')
                                    <span class="text-orange-700">
                                        Permiso especial
                                    </span>
                                @endif

                            </td>


                            <td class="px-3 py-2">

                                {{-- VACACIONES POR HORAS --}}

                                @if ($mov->tipo_movimiento == 'vacacion_horas')
                                    {{ \Carbon\Carbon::parse($mov->fecha_inicio)->format('d-m-Y') }}

                                    <br>

                                    <span class="text-gray-600 text-xs">

                                        {{ $mov->hora_inicio }} → {{ $mov->hora_fin }}

                                    </span>
                                @endif


                                {{-- VACACIONES POR DÍAS --}}

                                @if ($mov->tipo_movimiento == 'vacacion_dias')
                                    {{ \Carbon\Carbon::parse($mov->fecha_inicio)->format('d-m-Y') }}

                                    @if ($mov->fecha_fin)
                                        →

                                        {{ \Carbon\Carbon::parse($mov->fecha_fin)->format('d-m-Y') }}
                                    @endif
                                @endif


                                {{-- PERMISOS --}}

                                @if ($mov->tipo_movimiento == 'permiso_especial')
                                    {{ \Carbon\Carbon::parse($mov->fecha_inicio)->format('d-m-Y') }}
                                @endif

                            </td>


                            <td class="px-3 py-2 text-center">

                                @if ($mov->tipo_movimiento == 'vacacion_horas')
                                    {{ $mov->horas_equivalentes }}
                                @endif

                            </td>


                            <td class="px-3 py-2 text-center font-semibold">

                                @if ($mov->tipo_movimiento != 'permiso_especial')
                                    {{ $mov->dias_equivalentes }}
                                @endif

                            </td>


                            <td class="px-3 py-2">

                                {{ $mov->motivo }}

                            </td>


                            <td class="px-3 py-2">

                                @if ($mov->archivo_comprobante)
                                    <a href="{{ asset('storage/' . $mov->archivo_comprobante) }}" target="_blank"
                                        class="text-blue-600 underline">

                                        Ver archivo

                                    </a>
                                @endif

                            </td>
                            <td class="px-3 py-2 text-center">

                                <a href="{{ route('vacaciones.editar', $mov->id) }}"
                                    class="bg-yellow-600 text-white px-3 py-1 rounded text-xs">

                                    Editar

                                </a>

                            </td>
                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-4 text-gray-500">

                                No hay registros de vacaciones

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">

            <a href="{{ route('vacaciones.index') }}" class="bg-gray-700 text-white px-4 py-2 rounded">

                Volver

            </a>

        </div>
        <div class="mt-10">

            <h3 class="text-xl font-bold mb-4">
                Bitácora de cambios
            </h3>

            <div class="overflow-x-auto">

                <table class="min-w-full bg-white border text-sm">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="px-3 py-2">Fecha</th>
                            <th class="px-3 py-2">Acción</th>
                            <th class="px-3 py-2">Detalle</th>
                            <th class="px-3 py-2">Usuario</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($bitacora as $log)
                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-3 py-2">
                                    {{ \Carbon\Carbon::parse($log->fecha_evento)->format('d-m-Y H:i') }}
                                </td>

                                <td class="px-3 py-2 font-semibold text-blue-700">
                                    {{ $log->accion }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $log->detalle }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $log->usuario }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-4 text-gray-500">
                                    No hay registros en la bitácora
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-app-layout>
