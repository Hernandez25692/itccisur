<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Rutas de Cobranza</h1>
                <p class="text-sm text-gray-500">
                    Rutas manuales y sugeridas por el sistema.
                </p>
            </div>

            {{-- GENERAR RUTA SUGERIDA --}}
            <form method="POST" action="{{ route('cobranza.rutas.sugerir') }}"
                class="flex items-center gap-2 bg-white border rounded-xl p-2">
                @csrf

                <input type="date" name="fecha_ruta" class="rounded-lg border-gray-300 text-sm" required>

                <input type="number" name="ventana_dias_cobro" class="rounded-lg border-gray-300 text-sm w-24"
                    value="7" min="1" max="30" title="Días próximos a cobro">

                <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    + Sugerir ruta
                </button>
            </form>
        </div>

        {{-- MENSAJES --}}
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

        {{-- LISTADO --}}
        <div class="bg-white rounded-2xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Gestor</th>
                        <th class="px-4 py-3 text-center">Empresas</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rutas as $ruta)
                        @php
                            $badge = match ($ruta->estado) {
                                'sugerida' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'asignada' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'en_ruta' => 'bg-green-50 text-green-700 border-green-200',
                                'finalizada' => 'bg-gray-100 text-gray-700 border-gray-200',
                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                            };
                        @endphp

                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($ruta->fecha_ruta)->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                {{ $ruta->nombre ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 text-xs rounded-full border {{ $badge }}">
                                    {{ strtoupper(str_replace('_', ' ', $ruta->estado)) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @php
                                    $gestorIds = $ruta->empresas->pluck('pivot.gestor_id')->filter()->unique();

                                    $gestores = \App\Models\User::whereIn('id', $gestorIds)->pluck('name');
                                @endphp

                                @if ($ruta->estado === 'sugerida')
                                    <span class="text-gray-400">— SIN ASIGNAR —</span>
                                @elseif ($gestores->count() === 1)
                                    {{ $gestores->first() }}
                                @elseif ($gestores->count() > 1)
                                    <span class="text-blue-600 font-semibold">
                                        {{ $gestores->join(', ') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>



                            <td class="px-4 py-3 text-center">
                                {{ $ruta->empresas_count }}

                            </td>

                            <td class="px-4 py-3 text-right space-x-2">
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
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                No hay rutas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div>
            {{ $rutas->links() }}
        </div>

    </div>
</x-app-layout>
