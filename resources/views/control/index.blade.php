<x-app-layout>

    <div class="max-w-6xl mx-auto py-6">
        <h1 class="text-3xl font-bold mb-6 text-[#0c1c3c]">
            Control de Licencias, Dominios y Servicios TI
        </h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
            <div class="flex justify-end mb-4">
                <a href="{{ route('control.create') }}"
                    class="bg-[#b79a37] text-white px-4 py-2 rounded hover:bg-[#d1b45e] transition">
                    + Nuevo Registro
                </a>
            </div>
        
        @if ($alertas->count() > 0)
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6 border border-red-300">
                <h2 class="font-bold text-lg mb-2">⚠️ Alertas de vencimiento (próximos 15 días)</h2>

                <ul class="space-y-1">
                    @foreach ($alertas as $alerta)
                        <li>
                            <strong>{{ $alerta->actividad }}</strong> — vence el
                            <span
                                class="font-semibold">{{ \Carbon\Carbon::parse($alerta->fecha_vencimiento)->format('d/m/Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-[#0c1c3c] text-white">
                    <tr>
                        <th class="p-3 text-left">Actividad</th>
                        <th class="p-3 text-left">Tipo</th>
                        <th class="p-3 text-left">Fecha Ejecución</th>
                        <th class="p-3 text-left">Vence</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($recordatorios as $item)
                        @php
                            $hoy = now();
                            $vence = \Carbon\Carbon::parse($item->fecha_vencimiento);

                            // AHORA: positivo = FUTURO, negativo = VENCIDO
                            $dias = $hoy->diffInDays($vence, false);

                            // Solo alerta si está en el futuro y dentro del rango de recordatorio
                            $alerta_15 = $dias > 0 && $dias <= $item->dias_recordatorio;
                        @endphp


                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $item->actividad }}</td>
                            <td class="p-3">{{ $item->tipo ?? '—' }}</td>
                            <td class="p-3">
                                {{ $item->fecha_ejecucion ? \Carbon\Carbon::parse($item->fecha_ejecucion)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="p-3">{{ $vence->format('d/m/Y') }}</td>

                            <td class="p-3 text-center">

                                @if ($item->atendido)
                                    {{-- SI YA FUE ATENDIDO NO DEBE MOSTRAR ALERTAS --}}
                                    <span class="px-3 py-1 rounded-lg text-sm font-bold bg-blue-100 text-blue-700">
                                        ✔ Atendido
                                    </span>
                                @else
                                    {{-- CÁLCULO NORMAL PARA NO ATENDIDOS --}}

                                    @if ($dias < 0)
                                        {{-- YA VENCIDO --}}
                                        <span class="px-3 py-1 rounded-lg text-sm font-bold bg-red-100 text-red-700">
                                            Vencido (hace {{ intval(abs($dias)) }} días)
                                        </span>
                                    @elseif($dias == 0)
                                        {{-- HOY --}}
                                        <span
                                            class="px-3 py-1 rounded-lg text-sm font-bold bg-yellow-200 text-yellow-800">
                                            ¡Vence hoy!
                                        </span>
                                    @elseif($alerta_15)
                                        {{-- ALERTA: dentro de los días de recordatorio --}}
                                        <span
                                            class="px-3 py-1 rounded-lg text-sm font-bold bg-orange-100 text-orange-700">
                                            ⚠ Faltan {{ intval($dias) }} días
                                        </span>
                                    @else
                                        {{-- VIGENTE, PERO AÚN LEJOS --}}
                                        <span
                                            class="px-3 py-1 rounded-lg text-sm font-bold bg-green-100 text-green-700">
                                            Vigente (faltan {{ intval($dias) }} días)
                                        </span>
                                    @endif
                                @endif

                            </td>




                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('control.edit', $item->id) }}"
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Editar
                                    </a>
                                    @role('admin_ti')
                                        <form action="{{ route('control.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
