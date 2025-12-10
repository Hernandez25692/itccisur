<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
        }

        .activity-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4f8 0%, #e6eef7 100%);
            padding: 2rem;
        }

        .activity-card {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(12, 28, 60, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .activity-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 2rem;
            border-bottom: 4px solid var(--gold-primary);
        }

        .activity-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .activity-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
        }

        .activity-body {
            padding: 2.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label-required::after {
            content: ' *';
            color: #EF4444;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            color: #1a202c;
            background: white;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.15);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .file-upload {
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--gold-light);
            background: #f0f9ff;
        }

        .file-input {
            display: none;
        }

        .file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            color: #4a5568;
            cursor: pointer;
        }

        .file-icon {
            color: var(--gold-primary);
            font-size: 2rem;
        }

        .file-info {
            font-size: 0.875rem;
            color: #718096;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-submit {
            flex: 1;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-light) 100%);
            color: white;
            font-weight: 600;
            font-size: 1.05rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(197, 160, 73, 0.3);
        }

        .btn-cancel {
            padding: 1rem 2rem;
            background: white;
            color: #4a5568;
            font-weight: 600;
            font-size: 1.05rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-cancel:hover {
            background: #f7fafc;
            border-color: var(--gold-light);
            color: var(--gold-primary);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-icon {
            color: #EF4444;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .modal-title {
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .modal-text {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .modal-btn {
            padding: 0.75rem 2rem;
            background: var(--primary-medium);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .activity-container {
                padding: 1rem;
            }
            
            .activity-body {
                padding: 1.5rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="activity-container">
        <div class="activity-card">
            <div class="activity-header">
                <h1 class="activity-title">Registrar Actividad</h1>
                <p class="activity-subtitle">Bitácora del Departamento de Sistemas y TI</p>
            </div>

            <div class="activity-body">
                <form method="POST" action="{{ route('bitacora.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label form-label-required">Título</label>
                        <input type="text" name="titulo" class="form-input" required placeholder="Breve descripción de la actividad">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-textarea" placeholder="Detalles de la actividad realizada"></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label form-label-required">Tipo de Falla</label>
                            <select name="tipo_falla" class="form-select" required>
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

                        <div class="form-group">
                            <label class="form-label form-label-required">Equipo Afectado</label>
                            <select name="equipo_afectado" class="form-select" required>
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

                    <div class="form-group">
                        <label class="form-label form-label-required">Ubicación</label>
                        <select name="ubicacion" class="form-select" required>
                            <option value="DE">Dirección Ejecutiva</option>
                            <option value="GOR">Gerencia de Operaciones Registrales</option>
                            <option value="GAF">Gerencia Administrativa y Financiera</option>
                            <option value="GSEA">Gerencia de Servicios Empresariales y Afiliaciones</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label form-label-required">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="resuelto">Resuelto</option>
                        </select>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label form-label-required">Fecha</label>
                            <input type="date" name="fecha" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Prioridad</label>
                            <select name="prioridad" class="form-select">
                                <option value="baja">Baja</option>
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                                <option value="critica">Crítica</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Hora inicio</label>
                            <input type="time" name="hora_inicio" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hora fin</label>
                            <input type="time" name="hora_fin" class="form-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Solución aplicada</label>
                        <textarea name="solucion_aplicada" class="form-textarea" placeholder="Describa la solución implementada"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Evidencia (opcional)</label>
                        <div class="file-upload">
                            <input type="file" name="evidencia" id="evidenciaInput" class="file-input" 
                                   accept=".png,.jpg,.jpeg,.pdf,.doc,.docx">
                            <label for="evidenciaInput" class="file-label">
                                <div class="file-icon">📎</div>
                                <span>Subir archivo</span>
                                <div class="file-info">PNG, JPG, PDF, DOC, DOCX (Máx. 10MB)</div>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ url()->previous() }}" class="btn-cancel">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-submit">
                            Guardar Actividad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Error -->
    <div class="modal-overlay" id="modalError">
        <div class="modal-content">
            <div class="modal-icon">⚠️</div>
            <h3 class="modal-title">Archivo no permitido</h3>
            <p class="modal-text">
                Solo se aceptan archivos PNG, JPG, JPEG, PDF, DOC y DOCX.
            </p>
            <button onclick="cerrarModal()" class="modal-btn">
                Entendido
            </button>
        </div>
    </div>

    <script>
        const evidenciaInput = document.getElementById('evidenciaInput');
        const modalError = document.getElementById('modalError');

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
                this.value = "";
                abrirModal();
            }
        });

        function abrirModal() {
            modalError.classList.add('active');
        }

        function cerrarModal() {
            modalError.classList.remove('active');
        }

        // Cerrar modal al hacer clic fuera
        modalError.addEventListener('click', (e) => {
            if (e.target === modalError) {
                cerrarModal();
            }
        });
    </script>
</x-app-layout>