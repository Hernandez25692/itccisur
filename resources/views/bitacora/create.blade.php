<x-app-layout>

    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">

        <h2 class="text-xl font-bold mb-4">Registrar Actividad</h2>

        <form method="POST" action="{{ route('bitacora.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Título --}}
            <div class="mb-4">
                <label class="font-semibold">Título *</label>
                <input type="text" name="titulo" class="form-input w-full" required>
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label class="font-semibold">Descripción</label>
                <textarea name="descripcion" class="form-input w-full"></textarea>
            </div>

            {{-- Catálogos estandarizados --}}
            <div class="grid grid-cols-2 gap-4 mb-4">

                {{-- Tipo de falla --}}
                <div>
                    <label class="font-semibold">Tipo de Falla *</label>
                    <select name="tipo_falla" class="form-input w-full" required>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Red">Red</option>
                        <option value="Impresora">Impresora</option>
                        <option value="Energía">Energía</option>
                        <option value="Correo">Correo</option>
                        <option value="Usuario / Permisos">Usuario / Permisos</option>
                        <option value="Servidor">Servidor</option>
                        <option value="Internet">Internet</option>
                        <option value="Aplicación Interna">Aplicación Interna</option>
                    </select>
                </div>

                {{-- Equipo afectado --}}
                <div>
                    <label class="font-semibold">Equipo Afectado *</label>
                    <select name="equipo_afectado" class="form-input w-full" required>
                        <option value="PC Escritorio">PC Escritorio</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Switch">Switch</option>
                        <option value="Router">Router</option>
                        <option value="Access Point">Access Point</option>
                        <option value="Servidor">Servidor</option>
                        <option value="Impresora">Impresora</option>
                        <option value="UPS">UPS</option>
                        <option value="Sistema Interno">Sistema Interno</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            {{-- Ubicación --}}
            <div class="mb-4">
                <label class="font-semibold">Ubicación *</label>
                <select name="ubicacion" class="form-input w-full" required>
                    <option value="DE">Dirección Ejecutiva</option>
                    <option value="GOR">Gerencia de Operaciones Registrales</option>
                    <option value="GAF">Gerencia Administrativa y Financiera</option>
                    <option value="GSEA">Gerencia de Servicios Empresariales y Afiliaciones</option>
                </select>
            </div>

            {{-- Estado --}}
            <div class="mb-4">
                <label class="font-semibold">Estado *</label>
                <select name="estado" class="form-input w-full" required>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_proceso">En Proceso</option>
                    <option value="resuelto">Resuelto</option>
                </select>
            </div>



            {{-- Fecha y prioridad --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="font-semibold">Fecha *</label>
                    <input type="date" name="fecha" class="form-input w-full" required>
                </div>

                <div>
                    <label class="font-semibold">Prioridad *</label>
                    <select name="prioridad" class="form-input w-full">
                        <option value="baja">Baja</option>
                        <option value="media" selected>Media</option>
                        <option value="alta">Alta</option>
                        <option value="critica">Crítica</option>
                    </select>
                </div>
            </div>

            {{-- Horas --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio" class="form-input w-full">
                </div>
                <div>
                    <label>Hora fin</label>
                    <input type="time" name="hora_fin" class="form-input w-full">
                </div>
            </div>

            {{-- Solución --}}
            <div class="mb-4">
                <label class="font-semibold">Solución aplicada</label>
                <textarea name="solucion_aplicada" class="form-input w-full"></textarea>
            </div>

            {{-- Evidencia --}}
            <div class="mb-4">
                <label class="font-semibold">Evidencia (opcional)</label>
                <input type="file" name="evidencia" id="evidenciaInput" accept=".png,.jpg,.jpeg,.pdf,.doc,.docx"
                    class="block mt-1">

            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Guardar actividad
            </button>

        </form>

    </div>
    <!-- MODAL DE ERROR -->
    <div id="modalError" class="fixed inset-0 bg-black/60 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md text-center">
            <h2 class="text-xl font-bold text-red-600 mb-3">Archivo no permitido</h2>
            <p class="text-gray-700 mb-6">
                Solo se aceptan archivos PNG, JPG, JPEG, PDF, DOC y DOCX.
            </p>
            <button onclick="cerrarModal()" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Entendido
            </button>
        </div>
    </div>

    <script>
        const evidenciaInput = document.getElementById('evidenciaInput');

        evidenciaInput.addEventListener('change', function() {
            const archivo = this.files[0];
            if (!archivo) return;

            const extensionesPermitidas = [
                'image/png',
                'image/jpg',
                'image/jpeg',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            if (!extensionesPermitidas.includes(archivo.type)) {
                this.value = ""; // Limpia el input
                abrirModal();
            }
        });

        function abrirModal() {
            document.getElementById('modalError').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modalError').classList.add('hidden');
        }
    </script>

</x-app-layout>
