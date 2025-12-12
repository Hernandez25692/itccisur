<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                📌 Detalle de Publicación
            </h1>

            <div class="flex gap-2">
                <a href="{{ route('calendario-editorial.edit', $calendarioEditorial) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Editar
                </a>

                @role('admin_ti')
                    @if ($calendarioEditorial->estado !== 'publicado')
                        <form method="POST" action="{{ route('calendario-editorial.publicar', $calendarioEditorial) }}">
                            @csrf
                            <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">
                                Marcar publicado
                            </button>
                        </form>
                    @endif
                @endrole
            </div>
        </div>

        <!-- BLOQUE PRINCIPAL -->
        <div class="bg-white shadow rounded-xl p-6 space-y-6">

            <!-- Estado -->
            <div>
                <span
                    class="px-3 py-1 rounded-full text-sm font-semibold
                    @if ($calendarioEditorial->estado === 'publicado') bg-green-200 text-green-800
                    @elseif($calendarioEditorial->estado === 'pendiente') bg-orange-200 text-orange-800
                    @elseif($calendarioEditorial->estado === 'reprogramado') bg-yellow-200 text-yellow-800
                    @else bg-red-200 text-red-800 @endif">
                    {{ strtoupper($calendarioEditorial->estado) }}
                </span>
            </div>

            <!-- PROGRAMACIÓN -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div><strong>Semana:</strong> {{ $calendarioEditorial->semana }}</div>
                <div><strong>Día:</strong> {{ $calendarioEditorial->dia }}</div>
                <div><strong>Fecha:</strong> {{ $calendarioEditorial->fecha_publicacion->format('d/m/Y') }}
                    {{ $calendarioEditorial->hora }}</div>
            </div>

            <hr>

            <!-- CONTENIDO -->
            <div>
                <h2 class="font-semibold text-gray-700">Tema</h2>
                <p class="mt-1 text-gray-800">{{ $calendarioEditorial->tema }}</p>
            </div>

            <div>
                <h2 class="font-semibold text-gray-700">Encabezado</h2>
                <p class="mt-1 text-gray-800 whitespace-pre-line">
                    {{ $calendarioEditorial->encabezado }}
                </p>
            </div>

            <!-- FORMATOS -->
            <div>
                <h2 class="font-semibold text-gray-700">Tipo de contenido</h2>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach ($calendarioEditorial->contenido ?? [] as $c)
                        <span class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $c }}</span>
                    @endforeach
                </div>
            </div>

            <!-- REDES -->
            <div>
                <h2 class="font-semibold text-gray-700">Publicar en</h2>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach ($calendarioEditorial->publicar_en ?? [] as $r)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $r }}</span>
                    @endforeach
                </div>
            </div>

            <!-- ENLACE -->
            <div>
                <h2 class="font-semibold text-gray-700">Enlace publicado</h2>
                @if ($calendarioEditorial->enlace)
                    <a href="{{ $calendarioEditorial->enlace }}" target="_blank" class="text-blue-600 underline">
                        {{ $calendarioEditorial->enlace }}
                    </a>
                @else
                    <span class="text-gray-400">Aún no publicado</span>
                @endif
            </div>

            <!-- ADJUNTO -->
            @if ($calendarioEditorial->adjunto_path)
                <div>
                    <h2 class="font-semibold text-gray-700">Adjunto</h2>
                    <a href="{{ asset('storage/' . $calendarioEditorial->adjunto_path) }}" target="_blank"
                        class="text-blue-600 underline">
                        {{ $calendarioEditorial->adjunto_nombre }}
                    </a>
                </div>
            @endif

            <!-- AUDITORÍA -->
            <div class="text-xs text-gray-500 border-t pt-3">
                Registrado por: {{ $calendarioEditorial->creador?->name }} |
                Publicado por: {{ $calendarioEditorial->publicadoPor?->name ?? '—' }}
            </div>

        </div>
    </div>
</x-app-layout>
