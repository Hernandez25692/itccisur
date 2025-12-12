<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                📅 Calendario Editorial – Redes Sociales
            </h1>

            @role('admin_ti|calendario')
                <a href="{{ route('calendario-editorial.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    + Nueva Publicación
                </a>
            @endrole
        </div>

        <!-- Tabla -->
        <div class="bg-white shadow rounded-xl overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">Semana</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Tema</th>
                        <th class="px-4 py-3">Área</th>
                        <th class="px-4 py-3">Publicar en</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                        <th class="px-4 py-3">Enlace</th>

                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($registros as $item)
                        @php
                            $estadoColor = match ($item->estado) {
                                'publicado' => 'bg-green-50 text-green-700',
                                'pendiente' => 'bg-orange-50 text-orange-700',
                                'reprogramado' => 'bg-yellow-50 text-yellow-700',
                                'cancelado' => 'bg-red-50 text-red-700',
                                default => '',
                            };
                        @endphp

                        <tr class="{{ $estadoColor }}">
                            <td class="px-4 py-3 font-semibold">
                                {{ $item->semana }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->fecha_publicacion->format('d/m/Y') }}
                                <div class="text-xs text-gray-500">
                                    {{ $item->dia }} {{ $item->hora }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $item->tema }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ Str::limit($item->encabezado, 80) }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->area ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($item->publicar_en)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($item->publicar_en as $red)
                                            <span class="px-2 py-1 bg-gray-200 rounded text-xs">
                                                {{ $red }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="px-4 py-3 font-semibold capitalize">
                                {{ $item->estado }}
                            </td>

                            <td class="px-4 py-3 text-center space-x-2">

                                <a href="{{ route('calendario-editorial.edit', $item) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    Editar
                                </a>

                                @role('admin_ti')
                                    @if ($item->estado !== 'publicado')
                                        <form action="{{ route('calendario-editorial.publicar', $item) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button class="text-green-700 hover:underline text-sm"
                                                onclick="return confirm('¿Marcar como publicado?')">
                                                Publicar
                                            </button>
                                        </form>
                                    @endif
                                @endrole
                                <a href="{{ route('calendario-editorial.show', $item) }}"
                                    class="text-indigo-600 hover:underline text-sm">
                                    Ver
                                </a>

                            </td>
                            <td class="px-4 py-3">
                                @if ($item->enlace)
                                    <a href="{{ $item->enlace }}" target="_blank"
                                        class="text-blue-600 underline text-xs">
                                        Ver publicación
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                No hay publicaciones registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
