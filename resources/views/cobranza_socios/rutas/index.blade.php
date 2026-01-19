<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Rutas de Cobranza</h1>
                <p class="text-sm text-gray-500">
                    Planificación, ejecución y control de rutas.
                </p>
            </div>
        </div>

        {{-- ================= GENERADOR DE RUTA ================= --}}
        @role('admin_ti|gerencia|cobranza')
            <form method="POST" action="{{ route('cobranza.rutas.sugerir') }}"
                class="bg-white border rounded-2xl p-5 grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                @csrf

                <div>
                    <label class="text-xs font-semibold text-gray-600">Fecha ruta</label>
                    <input type="date" name="fecha_ruta" class="w-full rounded-xl border-gray-300" required>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">Ventana (días)</label>
                    <input type="number" name="ventana_dias_cobro" value="7" min="1" max="30"
                        class="w-full rounded-xl border-gray-300">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">
                        Máx. empresas a visitar
                    </label>
                    <input type="number" name="max_empresas" value="10" min="4" max="100"
                        class="w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">
                        Agrupar por
                    </label>
                    <select name="criterio_agrupacion" class="w-full rounded-xl border-gray-300">
                        <option value="cercania" selected>
                            Cercanía geográfica (GPS)
                        </option>
                        <option value="zona">
                            Zona / Sector
                        </option>
                        <option value="municipio">
                            Municipio
                        </option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">
                        Punto de inicio de la ruta
                    </label>
                    <select name="punto_base" class="w-full rounded-xl border-gray-300">
                        <option value="oficina">
                            Oficina CCISUR
                        </option>
                        <option value="primer_cliente">
                            Primer cliente asignado
                        </option>
                    </select>
                </div>

                

                <div class="md:col-span-2 flex justify-end">
                    <button class="px-6 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                        + Generar ruta sugerida
                    </button>
                </div>
            </form>
        @endrole

        {{-- ================= MENSAJES ================= --}}
        @if (session('success'))
            <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= TABLA DE RUTAS ================= --}}
        <div class="bg-white rounded-2xl border overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-2 text-left">Fecha</th>
                        <th>Ruta</th>
                        <th>Estado</th>
                        <th>Gestores</th>
                        <th class="text-center">Empresas</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($rutas as $ruta)
                        @php
                            $badge = match ($ruta->estado) {
                                'sugerida' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'asignada' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'en_ruta' => 'bg-green-50 text-green-700 border-green-200',
                                'finalizada' => 'bg-gray-100 text-gray-700 border-gray-200',
                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                            };

                            $gestorIds = $ruta->empresas->pluck('pivot.gestor_id')->filter()->unique();
                            $gestores = \App\Models\User::whereIn('id', $gestorIds)->pluck('name');
                        @endphp

                        <tr class="hover:bg-gray-50">

                            {{-- Fecha --}}
                            <td class="px-3 py-2">
                                {{ $ruta->fecha_ruta->format('d/m/Y') }}
                            </td>

                            {{-- Nombre --}}
                            <td class="font-semibold text-gray-900">
                                {{ $ruta->nombre ?? '—' }}
                            </td>

                            {{-- Estado --}}
                            <td>
                                <span class="px-3 py-1 text-xs rounded-full border {{ $badge }}">
                                    {{ strtoupper(str_replace('_', ' ', $ruta->estado)) }}
                                </span>
                            </td>

                            {{-- Gestores --}}
                            <td>
                                @if ($ruta->estado === 'sugerida')
                                    <span class="text-gray-400">— SIN ASIGNAR —</span>
                                @elseif ($gestores->count() === 1)
                                    {{ $gestores->first() }}
                                @elseif ($gestores->count() > 1)
                                    <span class="text-blue-700 font-semibold">
                                        {{ $gestores->join(', ') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>

                            {{-- Empresas --}}
                            <td class="text-center font-semibold">
                                {{ $ruta->empresas_count }}
                            </td>

                            {{-- Acciones --}}
                            <td class="text-right space-x-3">
                                <a href="{{ route('cobranza.rutas.show', $ruta) }}"
                                    class="text-blue-600 hover:underline">
                                    Ver
                                </a>

                                @if ($ruta->estado === 'sugerida')
                                    <form method="POST" action="{{ route('cobranza.rutas.confirmar', $ruta) }}"
                                        class="inline">
                                        @csrf
                                        <button class="text-green-600 hover:underline">
                                            Confirmar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-400">
                                No hay rutas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= PAGINACIÓN ================= --}}
        <div>
            {{ $rutas->links() }}
        </div>

    </div>
</x-app-layout>
