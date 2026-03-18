<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        

        <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 mb-8 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Historial de vacaciones</h2>
                    <p class="text-sm text-slate-200">
                        Empleado: <span class="font-semibold text-white">{{ $empleado->nombre_completo }}</span>
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('vacaciones.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-amber-400 to-amber-500 text-slate-900 font-semibold shadow hover:from-amber-300 hover:to-amber-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver al control de vacaciones
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 mb-6 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-xl bg-white shadow-sm overflow-hidden mb-10">
            <header class="flex items-center justify-between px-6 py-4 border-b border-sky-200 bg-sky-50">
                <h3 class="text-lg font-semibold text-sky-700">Movimientos</h3>
                <span class="text-sm text-sky-600">Registros de vacaciones y permisos</span>
            </header>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Tipo</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Periodo</th>
                            <th class="px-4 py-3 text-center font-semibold text-sky-700">Horas</th>
                            <th class="px-4 py-3 text-center font-semibold text-sky-700">Días</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Motivo</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Comprobante</th>
                            <th class="px-4 py-3 text-center font-semibold text-sky-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 odd:bg-white even:bg-sky-50/50">
                        @forelse ($movimientos as $mov)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold">
                                    @if ($mov->tipo_movimiento == 'vacacion_dias')
                                        <span class="text-emerald-700">Vacación (días)</span>
                                    @endif
                                    @if ($mov->tipo_movimiento == 'vacacion_horas')
                                        <span class="text-sky-700">Vacación (horas)</span>
                                    @endif
                                    @if ($mov->tipo_movimiento == 'permiso_especial')
                                        <span class="text-orange-600">Permiso especial</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($mov->tipo_movimiento == 'vacacion_horas')
                                        {{ \Carbon\Carbon::parse($mov->fecha_inicio)->format('d-m-Y') }}
                                        <div class="text-xs text-slate-500">
                                            {{ $mov->hora_inicio }} → {{ $mov->hora_fin }}
                                        </div>
                                    @endif

                                    @if ($mov->tipo_movimiento == 'vacacion_dias')
                                        {{ \Carbon\Carbon::parse($mov->fecha_inicio)->format('d-m-Y') }}
                                        @if ($mov->fecha_fin)
                                            → {{ \Carbon\Carbon::parse($mov->fecha_fin)->format('d-m-Y') }}
                                        @endif
                                    @endif

                                    @if ($mov->tipo_movimiento == 'permiso_especial')
                                        {{ \Carbon\Carbon::parse($mov->fecha_inicio)->format('d-m-Y') }}
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($mov->tipo_movimiento == 'vacacion_horas')
                                        {{ $mov->horas_equivalentes }}
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    @if ($mov->tipo_movimiento != 'permiso_especial')
                                        {{ $mov->dias_equivalentes }}
                                    @endif
                                </td>

                                <td class="px-4 py-3">{{ $mov->motivo }}</td>

                                <td class="px-4 py-3">
                                    @if ($mov->archivo_comprobante)
                                        <a href="{{ asset('storage/' . $mov->archivo_comprobante) }}" target="_blank"
                                            class="text-sky-600 hover:underline">
                                            Ver archivo
                                        </a>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('vacaciones.editar', $mov->id) }}"
                                        class="inline-flex items-center justify-center rounded-md bg-amber-500 px-3 py-1 text-xs font-semibold text-white hover:bg-amber-600 transition">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                                    No hay registros de vacaciones
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-xl bg-white shadow-sm overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 border-b border-sky-200 bg-sky-50">
                <div>
                    <h3 class="text-lg font-semibold text-sky-700">Bitácora de cambios</h3>
                    <p class="text-sm text-sky-600 mt-1">Registro de acciones realizadas sobre vacaciones.</p>
                </div>
            </header>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Fecha</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Acción</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Detalle</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-700">Usuario</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 odd:bg-white even:bg-sky-50/50">
                        @forelse ($bitacora as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($log->fecha_evento)->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-amber-700">{{ $log->accion }}</td>
                                <td class="px-4 py-3">{{ $log->detalle }}</td>
                                <td class="px-4 py-3">{{ $log->usuario }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                    No hay registros en la bitácora
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
