<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">
            ➕ Nueva Publicación – Calendario Editorial
        </h1>

        <form method="POST" action="{{ route('calendario-editorial.store') }}" enctype="multipart/form-data"
            class="bg-white shadow rounded-xl p-6 space-y-6">
            @csrf

            <!-- Programación -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Programación</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm">Semana</label>
                        <input type="number" name="semana" class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label class="text-sm">Día</label>
                        <select name="dia" class="w-full border rounded p-2" required>
                            <option value="">Seleccione</option>
                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <option value="{{ $dia }}">{{ $dia }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Fecha publicación</label>
                        <input type="date" name="fecha_publicacion" class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label class="text-sm">Hora</label>
                        <input type="time" name="hora" class="w-full border rounded p-2">
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Contenido</h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm">Tema / Título</label>
                        <textarea name="tema" rows="2" class="w-full border rounded p-2" required></textarea>
                    </div>

                    <div>
                        <label class="text-sm">Área</label>
                        <select name="area" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach (['Día festivo', 'Calendario', 'Bienvenida socios', 'Post capacitación', 'Servicio empresarial', 'Comunicado', 'Nota de duelo', 'Nota de prensa', 'Evento', 'Empresa afiliada Ofrece', 'Frase motivacional', 'Campaña muevete y emprende', 'Campaña Consejo laboral - video', 'Video resumen capacitación', 'Video resumen evento CCISUR', 'Servicios de Intermediación laboral', 'Alquiler de salón de eventos', 'Espacio de coworking', 'Alquiler de espacio para zona bancaria', 'Campaña de registro', 'Campaña de afiliación', 'Campaña informativa CAS - consejos'] as $area)
                                <option value="{{ $area }}">{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Encabezado</label>
                        <textarea name="encabezado" rows="3" class="w-full border rounded p-2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Tipo de contenido -->
            <div>
                <h2 class="font-semibold text-gray-700 mb-3">Tipo de contenido</h2>
                <div class="flex flex-wrap gap-4">
                    @foreach (['EN VIVO', 'IMAGEN', 'CARRUSEL', 'HISTORIA', 'VIDEO'] as $tipo)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="contenido[]" value="{{ $tipo }}">
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
                            <input type="checkbox" name="publicar_en[]" value="{{ $red }}">
                            <span>{{ $red }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Extras -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm">Etiquetas (páginas a etiquetar)</label>
                    <input type="text" name="etiquetas" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="text-sm">Adjunto (imagen, video o documento)</label>
                    <input type="file" name="adjunto" class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="text-sm">Comentario</label>
                <textarea name="comentario" rows="3" class="w-full border rounded p-2"></textarea>
            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('calendario-editorial.index') }}" class="px-4 py-2 border rounded-lg text-gray-600">
                    Cancelar
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
