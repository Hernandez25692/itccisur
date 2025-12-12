<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">
            ✏️ Editar Publicación – Calendario Editorial
        </h1>

        <form method="POST" action="{{ route('calendario-editorial.update', $calendarioEditorial) }}"
            enctype="multipart/form-data" class="bg-white shadow rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Programación -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Programación</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm">Semana</label>
                        <input type="number" name="semana" value="{{ old('semana', $calendarioEditorial->semana) }}"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label class="text-sm">Día</label>
                        <select name="dia" class="w-full border rounded p-2" required>
                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <option value="{{ $dia }}" @selected(old('dia', $calendarioEditorial->dia) === $dia)>
                                    {{ $dia }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Fecha publicación</label>
                        <input type="date" name="fecha_publicacion"
                            value="{{ old('fecha_publicacion', $calendarioEditorial->fecha_publicacion->format('Y-m-d')) }}"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label class="text-sm">Hora</label>
                        <input type="time" name="hora" value="{{ old('hora', $calendarioEditorial->hora) }}"
                            class="w-full border rounded p-2">
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Contenido</h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm">Tema / Título</label>
                        <textarea name="tema" rows="2" class="w-full border rounded p-2" required>{{ old('tema', $calendarioEditorial->tema) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm">Área</label>
                        <select name="area" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach (['Día festivo', 'Calendario', 'Bienvenida socios', 'Post capacitación', 'Servicio empresarial', 'Comunicado', 'Nota de duelo', 'Nota de prensa', 'Evento', 'Empresa afiliada Ofrece', 'Frase motivacional', 'Campaña muevete y emprende', 'Campaña Consejo laboral - video', 'Video resumen capacitación', 'Video resumen evento CCISUR', 'Servicios de Intermediación laboral', 'Alquiler de salón de eventos', 'Espacio de coworking', 'Alquiler de espacio para zona bancaria', 'Campaña de registro', 'Campaña de afiliación', 'Campaña informativa CAS - consejos'] as $area)
                                <option value="{{ $area }}" @selected(old('area', $calendarioEditorial->area) === $area)>{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Encabezado</label>
                        <textarea name="encabezado" rows="3" class="w-full border rounded p-2">{{ old('encabezado', $calendarioEditorial->encabezado) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tipo de contenido -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Tipo de contenido</h2>
                <div class="flex flex-wrap gap-4">
                    @foreach (['EN VIVO', 'IMAGEN', 'CARRUSEL', 'HISTORIA', 'VIDEO'] as $tipo)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="contenido[]" value="{{ $tipo }}"
                                @checked(in_array($tipo, old('contenido', $calendarioEditorial->contenido ?? [])))>
                            <span>{{ $tipo }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Publicar en -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Publicar en</h2>
                <div class="flex flex-wrap gap-4">
                    @foreach (['FACEBOOK', 'INSTAGRAM', 'X', 'LINKEDIN', 'YOUTUBE', 'WHATSAPP', 'TIKTOK', 'OTRA'] as $red)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="publicar_en[]" value="{{ $red }}"
                                @checked(in_array($red, old('publicar_en', $calendarioEditorial->publicar_en ?? [])))>
                            <span>{{ $red }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Extras -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm">Etiquetas</label>
                    <input type="text" name="etiquetas"
                        value="{{ old('etiquetas', $calendarioEditorial->etiquetas) }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="text-sm">Adjunto</label>
                    <input type="file" name="adjunto" class="w-full border rounded p-2">
                    @if ($calendarioEditorial->adjunto_path)
                        <p class="text-xs text-gray-500 mt-1">
                            Archivo actual: {{ $calendarioEditorial->adjunto_nombre }}
                        </p>
                    @endif
                </div>
            </div>

            <div>
                <label class="text-sm">Comentario</label>
                <textarea name="comentario" rows="3" class="w-full border rounded p-2">{{ old('comentario', $calendarioEditorial->comentario) }}</textarea>
            </div>

            <!-- SOLO ADMIN TI -->
            @role('admin_ti')
                <div class="border-t pt-4 space-y-4">
                    <h2 class="font-semibold text-gray-700">Control administrativo</h2>

                    <div>
                        <label class="text-sm">Estado</label>
                        <select name="estado" class="w-full border rounded p-2">
                            @foreach (['pendiente', 'publicado', 'reprogramado', 'cancelado'] as $estado)
                                <option value="{{ $estado }}" @selected(old('estado', $calendarioEditorial->estado) === $estado)>
                                    {{ ucfirst($estado) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Enlace de publicación</label>
                        <input type="text" name="enlace" value="{{ old('enlace', $calendarioEditorial->enlace) }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="text-sm">Nota administrativa</label>
                        <textarea name="nota_admin" rows="3" class="w-full border rounded p-2">{{ old('nota_admin', $calendarioEditorial->nota_admin) }}</textarea>
                    </div>
                </div>
            @endrole

            <!-- Acciones -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('calendario-editorial.index') }}" class="px-4 py-2 border rounded-lg text-gray-600">
                    Volver
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
