<x-app-layout>

    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">

        <h2 class="text-xl font-bold mb-4">Editar Actividad</h2>

        <form method="POST" action="{{ route('bitacora.update', $actividad->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Título --}}
            <div class="mb-4">
                <label class="font-semibold">Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo', $actividad->titulo) }}"
                    class="form-input w-full" required>
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label class="font-semibold">Descripción</label>
                <textarea name="descripcion" class="form-input w-full">{{ old('descripcion', $actividad->descripcion) }}</textarea>
            </div>

            {{-- Catálogos estandarizados --}}
            <div class="grid grid-cols-2 gap-4 mb-4">

                {{-- Tipo de falla --}}
                <div>
                    <label class="font-semibold">Tipo de Falla *</label>
                    <select name="tipo_falla" class="form-input w-full" required>
                        @foreach (['Hardware', 'Software', 'Red', 'Impresora', 'Energía', 'Correo', 'Usuario / Permisos', 'Servidor', 'Internet', 'Aplicación Interna'] as $item)
                            <option value="{{ $item }}" {{ $actividad->tipo_falla == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Equipo afectado --}}
                <div>
                    <label class="font-semibold">Equipo Afectado *</label>
                    <select name="equipo_afectado" class="form-input w-full" required>
                        @foreach (['PC Escritorio', 'Laptop', 'Switch', 'Router', 'Access Point', 'Servidor', 'Impresora', 'UPS', 'Sistema Interno', 'Otro'] as $item)
                            <option value="{{ $item }}"
                                {{ $actividad->equipo_afectado == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Ubicación --}}
            <div class="mb-4">
                <label class="font-semibold">Ubicación *</label>
                <select name="ubicacion" class="form-input w-full" required>
                    @foreach (['DE' => 'Dirección Ejecutiva', 'GOR' => 'Gerencia de Operaciones Registrales', 'GAF' => 'Gerencia Administrativa y Financiera', 'GSEA' => 'Gerencia de Servicios Empresariales y Afiliaciones'] as $key => $label)
                        <option value="{{ $key }}" {{ $actividad->ubicacion == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Estado --}}
            <div class="mb-4">
                <label class="font-semibold">Estado *</label>
                <select name="estado" class="form-input w-full" required>
                    @foreach (['pendiente' => 'Pendiente', 'en_proceso' => 'En Proceso', 'resuelto' => 'Resuelto'] as $key => $label)
                        <option value="{{ $key }}" {{ $actividad->estado == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Fecha --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="font-semibold">Fecha *</label>
                    <input type="date" name="fecha" value="{{ optional($actividad->fecha)->format('Y-m-d') }}"
                        class="form-input w-full" required>

                    required>
                </div>

                <div>
                    <label class="font-semibold">Prioridad *</label>
                    <select name="prioridad" class="form-input w-full">
                        @foreach (['baja', 'media', 'alta', 'critica'] as $p)
                            <option value="{{ $p }}" {{ $actividad->prioridad == $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Horas --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio"
                        value="{{ $actividad->hora_inicio ? \Carbon\Carbon::parse($actividad->hora_inicio)->format('H:i') : '' }}"
                        class="form-input w-full">

                </div>
                <div>
                    <label>Hora fin</label>
                    <input type="time" name="hora_fin"
                        value="{{ $actividad->hora_fin ? \Carbon\Carbon::parse($actividad->hora_fin)->format('H:i') : '' }}"
                        class="form-input w-full">

                </div>
            </div>

            {{-- Solución aplicada --}}
            <div class="mb-4">
                <label class="font-semibold">Solución aplicada</label>
                <textarea name="solucion_aplicada" class="form-input w-full">{{ $actividad->solucion_aplicada }}</textarea>
            </div>

            {{-- Evidencia --}}
            <div class="mb-4">
                <label class="font-semibold">Evidencia (opcional)</label>
                <input type="file" name="evidencia" class="block mt-1" accept="image/*">

                @if ($actividad->evidencia)
                    <p class="mt-2 text-sm">Evidencia actual:</p>
                    <img src="{{ asset('storage/' . $actividad->evidencia) }}" class="h-32 rounded shadow">
                @endif
            </div>

            <button class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                Actualizar Actividad
            </button>

        </form>

    </div>

</x-app-layout>
