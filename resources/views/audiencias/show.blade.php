<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-5 space-y-3">

            <h1 class="text-lg font-bold text-gray-800">
                Detalle de Audiencia
            </h1>

            <p><strong>Solicitante:</strong> {{ $audiencia->nombre_solicitante }}</p>
            <p><strong>Documento:</strong> {{ $audiencia->numero_documento }}</p>
            <p><strong>Fecha recepción:</strong> {{ $audiencia->fecha_recepcion->format('d/m/Y') }}</p>

            @if ($audiencia->fecha_hora_atencion)
                <p><strong>Atendido:</strong>
                    {{ \Carbon\Carbon::parse($audiencia->fecha_hora_atencion)->format('d/m/Y H:i') }}
                </p>
            @endif

            <p><strong>Motivo:</strong><br>{{ $audiencia->motivo }}</p>

            @if ($audiencia->dictamen)
                <p><strong>Dictamen:</strong><br>{{ $audiencia->dictamen }}</p>
            @endif

            <hr>

            <p class="text-xs text-gray-500">
                Registrado por: {{ $audiencia->creador->name ?? 'N/D' }}
            </p>

            <div class="flex justify-end gap-4 text-sm">
                <a href="{{ route('audiencias.edit', $audiencia->id) }}" class="text-blue-700 font-medium">
                    Editar
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
