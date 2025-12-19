<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4">

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6 space-y-6">

            <div class="text-center">
                <h1 class="text-xl font-bold text-gray-800">
                    Revisión de Antecedente Registral
                </h1>
                <p class="text-sm text-gray-500">Centro Asociado del Sur</p>
            </div>

            <div class="grid grid-cols-1 gap-3 text-sm text-gray-700">
                <p><strong>Circunscripción:</strong> {{ $registro->circunscripcion }}</p>
                <p><strong>Fecha de Recepción:</strong>
                    {{ \Carbon\Carbon::parse($registro->fecha_recepcion)->format('d/m/Y') }}
                </p>
            </div>

            <hr>

            <div class="space-y-2 text-sm">
                <p><strong>Solicitante:</strong> {{ $registro->solicitante_nombre }}</p>
                <p><strong>Dirección:</strong> {{ $registro->solicitante_direccion }}</p>
                <p><strong>N° Exequátur:</strong> {{ $registro->numero_exequatur }}</p>
                <p><strong>Asiento / Tomo / Matrícula:</strong> {{ $registro->asiento_tomo_matricula }}</p>
                <p><strong>Tipo de libro:</strong> {{ $registro->tipo_libro }}</p>
            </div>

            <hr>

            <div>
                <p class="font-semibold text-gray-700">Motivo</p>
                <p class="text-sm text-gray-600 whitespace-pre-line">
                    {{ $registro->motivo }}
                </p>
            </div>

            <hr>

            <!-- AUDITORÍA -->
            <div class="text-xs text-gray-500 space-y-1">
                <p>
                    Registrado por:
                    <strong>{{ $registro->creador->name ?? 'N/D' }}</strong>
                    ({{ $registro->created_at->format('d/m/Y H:i') }})
                </p>

                @if ($registro->editor)
                    <p>
                        Última edición:
                        <strong>{{ $registro->editor->name }}</strong>
                        ({{ $registro->updated_at->format('d/m/Y H:i') }})
                    </p>
                @endif
            </div>
            @if ($registro->comprobante_path)
                <hr>

                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">
                        Comprobante
                    </p>

                    <a href="{{ asset('storage/' . $registro->comprobante_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $registro->comprobante_path) }}"
                            class="max-w-full rounded-lg border">
                    </a>
                </div>
            @endif

            <div class="flex justify-between pt-4">
                <a href="{{ route('gor.antecedentes.index') }}" class="text-sm underline text-gray-600">
                    Volver
                </a>

                <a href="{{ route('gor.antecedentes.edit', $registro->id) }}"
                    class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    Editar
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
