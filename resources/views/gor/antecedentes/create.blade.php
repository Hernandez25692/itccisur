<x-app-layout>
    <style>
        :root {
            --primary-dark: #1e3a5f;
            --primary-medium: #2d5a8c;
            --primary-light: #3d7ab8;
            --accent: #f59e0b;
            --accent-light: #fbbf24;
            --success: #10b981;
            --danger: #ef4444;
            --gray-light: #f3f4f6;
            --gray-medium: #e5e7eb;
            --gray-dark: #374151;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        .public-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f0ff 100%);
            padding: 1rem;
        }

        .public-card {
            max-width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(30, 58, 95, 0.12);
            overflow: hidden;
            border: 1px solid rgba(61, 122, 184, 0.1);
        }

        @media (min-width: 640px) {
            .public-container {
                padding: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .public-card {
                max-width: 500px;
            }
        }

        /* Header */
        .public-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 1.5rem 1.25rem;
            color: white;
            text-align: center;
            position: relative;
        }

        .public-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
        }

        .public-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        @media (min-width: 640px) {
            .public-title {
                font-size: 1.5rem;
            }
        }

        .public-subtitle {
            opacity: 0.9;
            font-size: 0.875rem;
        }

        /* Form Body */
        .form-body {
            padding: 1.5rem 1.25rem;
        }

        @media (min-width: 640px) {
            .form-body {
                padding: 2rem;
            }
        }

        /* Error Messages */
        .error-container {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 3px solid var(--danger);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
            color: #dc2626;
        }

        .error-list li {
            padding: 0.25rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-list li::before {
            content: '⚠️';
            font-size: 0.875rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: var(--primary-dark);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        /* Radio Buttons */
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border: 2px solid var(--gray-medium);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .radio-label:hover,
        .radio-label:focus-within {
            border-color: var(--accent-light);
            background: rgba(245, 158, 11, 0.05);
        }

        .radio-label.selected {
            border-color: var(--accent);
            background: rgba(245, 158, 11, 0.1);
        }

        .radio-input {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--accent);
            flex-shrink: 0;
        }

        /* Form Inputs */
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid var(--gray-medium);
            border-radius: 10px;
            font-size: 1rem;
            color: var(--text-dark);
            background: white;
            transition: all 0.2s ease;
            -webkit-appearance: none;
        }

        .form-input::placeholder,
        .form-select::placeholder,
        .form-textarea::placeholder {
            color: var(--text-light);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed var(--gray-medium);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            background: var(--gray-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--accent-light);
            background: rgba(245, 158, 11, 0.05);
        }

        .file-input {
            display: none;
        }

        .file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-dark);
            cursor: pointer;
        }

        .file-icon {
            font-size: 1.5rem;
            color: var(--accent);
        }

        .file-info {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .camera-hint {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--text-dark);
            background: rgba(245, 158, 11, 0.1);
            padding: 0.5rem;
            border-radius: 6px;
            border-left: 3px solid var(--accent);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .submit-btn:hover,
        .submit-btn:active {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Section Dividers */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gray-medium), transparent);
            margin: 1.5rem 0;
        }

        /* Touch Optimizations */
        @media (hover: none) and (pointer: coarse) {
            .radio-label,
            .submit-btn,
            .file-upload {
                min-height: 44px;
            }

            .radio-label {
                padding: 1.25rem 1rem;
            }

            .submit-btn {
                min-height: 52px;
            }

            .form-input,
            .form-select,
            .form-textarea {
                font-size: 16px;
            }
        }

        /* iOS specific fixes */
        @supports (-webkit-touch-callout: none) {
            .form-select {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 1rem center;
                background-size: 1.25rem;
                padding-right: 3rem;
            }

            input[type="date"] {
                position: relative;
            }

            input[type="date"]::-webkit-calendar-picker-indicator {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: auto;
                height: auto;
                color: transparent;
                background: transparent;
            }

            input[type="date"]::-webkit-inner-spin-button,
            input[type="date"]::-webkit-clear-button {
                display: none;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .public-container {
                background: linear-gradient(135deg, #0f1e2e 0%, #1a2f45 100%);
            }

            .public-card {
                background: #1f2937;
                border-color: rgba(61, 122, 184, 0.2);
            }

            .form-label {
                color: #e0f0ff;
            }

            .form-input,
            .form-select,
            .form-textarea {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-input::placeholder,
            .form-select::placeholder,
            .form-textarea::placeholder {
                color: #9ca3af;
            }

            .radio-label {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .radio-label:hover,
            .radio-label:focus-within {
                background: rgba(245, 158, 11, 0.15);
            }

            .file-upload {
                background: #374151;
                border-color: #4b5563;
            }

            .file-label {
                color: #e5e7eb;
            }

            .file-info,
            .camera-hint {
                color: #9ca3af;
            }
        }
    </style>

    <div class="public-container">
        <div class="public-card">
            <!-- Header -->
            <div class="public-header">
                <h1 class="public-title">Revisión de Antecedentes Registrales</h1>
                <p class="public-subtitle">Centro Asociado del Sur</p>
            </div>

            <!-- Form Body -->
            <div class="form-body">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="error-container">
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('gor.antecedentes.store') }}" enctype="multipart/form-data"
                    id="registroForm">

                    @csrf

                    <!-- CIRCUNSCRIPCIÓN -->
                    <div class="form-group">
                        <label class="form-label">
                            Circunscripción Registral
                        </label>
                        <div class="radio-group">
                            <label class="radio-label" id="radioNacaome">
                                <input type="radio" name="circunscripcion" value="Nacaome - Valle" required
                                    class="radio-input" onchange="actualizarTipoLibro()">
                                <span>Nacaome / Valle</span>
                            </label>

                            <label class="radio-label" id="radioCholuteca">
                                <input type="radio" name="circunscripcion" value="Choluteca - Choluteca"
                                    class="radio-input" onchange="actualizarTipoLibro()">
                                <span>Choluteca / Choluteca</span>
                            </label>
                        </div>
                    </div>

                    <!-- FECHA -->
                    <div class="form-group">
                        <label class="form-label">
                            Fecha de Recepción
                        </label>
                        <input type="date" name="fecha_recepcion" class="form-input" required
                            max="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="section-divider"></div>

                    <!-- DATOS SOLICITANTE -->
                    <div class="form-group">
                        <h2 class="form-label">
                            Datos del Solicitante
                        </h2>

                        <div class="space-y-3">
                            <input type="text" name="solicitante_nombre" placeholder="Nombre completo"
                                class="form-input" required>

                            <textarea name="solicitante_direccion" rows="2" placeholder="Dirección" class="form-textarea"></textarea>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- DATOS REGISTRALES -->
                    <div class="form-group">
                        <h2 class="form-label">
                            Datos Registrales
                        </h2>

                        <div class="space-y-3">
                            <input type="text" name="numero_exequatur" placeholder="Número de Exequátur (opcional)"
                                class="form-input">

                            <input type="text" name="asiento_tomo_matricula" placeholder="Asiento / Tomo / Matrícula"
                                class="form-input">

                            <div>
                                <label class="form-label">Tipo de libro</label>
                                <select id="tipoLibro" name="tipo_libro" class="form-select">
                                    <option value="">[Seleccionar]</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- MOTIVO -->
                    <div class="form-group">
                        <label class="form-label">
                            Motivo de la Solicitud
                        </label>
                        <textarea name="motivo" rows="4" class="form-textarea" placeholder="Describa el motivo de la solicitud"></textarea>
                    </div>

                    <!-- COMPROBANTE -->
                    <div class="form-group">
                        <label class="form-label">
                            Comprobante (opcional)
                        </label>

                        <div class="file-upload">
                            <input type="file" name="comprobante" id="comprobanteInput" accept="image/*,.heic,.heif"
                                capture="environment" class="file-input">

                            <label for="comprobanteInput" class="file-label">
                                <div class="file-icon">📷</div>
                                <span>Tomar foto o seleccionar archivo</span>
                                <div class="file-info">
                                    Formatos: JPG, PNG, HEIC • Máx. 5MB
                                </div>
                            </label>
                        </div>

                        <div class="camera-hint">
                            💡 Puede tomar una foto directamente con la cámara de su dispositivo
                        </div>
                    </div>

                    <!-- BOTÓN -->
                    <button type="submit" class="submit-btn">
                        📋 Guardar Registro
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const librosCholuteca = {
            "01 - REGISTRO DE LA PROPIEDAD": "01 - REGISTRO DE LA PROPIEDAD",
            "02 - REGISTRO DE HIPOTECAS": "02 - REGISTRO DE HIPOTECAS",
            "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS": "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS",
            "04 - LIBROS INA": "04 - LIBROS INA",
            "05 - LIBRO TOMO CONSERVADOR": "05 - LIBRO TOMO CONSERVADOR",
            "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE": "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE",
            "07 - LIBRO DE PRESENTACIONES (DIARIO)": "07 - LIBRO DE PRESENTACIONES (DIARIO)",
            "10 - CERTIFICACIONES MUNICIPALES": "10 - CERTIFICACIONES MUNICIPALES",
            "11 - ANOTACIONES PREVENTIVAS": "11 - ANOTACIONES PREVENTIVAS",
            "12 - REINSCRIPCIONES": "12 - REINSCRIPCIONES",
            "13 - LIBRO DE SENTENCIAS": "13 - LIBRO DE SENTENCIAS",
            "14 - PROPIEDAD HORIZONTAL": "14 - PROPIEDAD HORIZONTAL",
            "15 - AUXILIAR DE PRENDAS": "15 - AUXILIAR DE PRENDAS",
            "16 - PRENDAS MERCANTILES": "16 - PRENDAS MERCANTILES",
            "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS": "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS",
            "18 - REGISTRO DE AERONAUTICA": "18 - REGISTRO DE AERONAUTICA",
            "21 - REGISTRO COMERCIANTE SOCIAL": "21 - REGISTRO COMERCIANTE SOCIAL",
            "22 - REGISTRO COMERCIANTE INDIVIDUAL": "22 - REGISTRO COMERCIANTE INDIVIDUAL",
            "24 - ESTABLECIMIENTO MERCANTIL": "24 - ESTABLECIMIENTO MERCANTIL",
            "30 - REGISTRO DE PODERES": "30 - REGISTRO DE PODERES",
            "32 - COMERCIANTE INDIVIDUAL (ANTIGUO)": "32 - COMERCIANTE INDIVIDUAL (ANTIGUO)",
            "33 - REGISTRO DE COMERCIO (ANTIGUO)": "33 - REGISTRO DE COMERCIO (ANTIGUO)",
            "34 - REGISTRO MERCANTIL (ANTIGUO)": "34 - REGISTRO MERCANTIL (ANTIGUO)",
            "19 - CIRCUNSCRIPCION": "19 - CIRCUNSCRIPCION",
            "OTROS": "OTROS"
        };

        const librosNacaome = {
            "01 - REGISTRO DE LA PROPIEDAD": "01 - REGISTRO DE LA PROPIEDAD",
            "02 - REGISTRO DE HIPOTECAS": "02 - REGISTRO DE HIPOTECAS",
            "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS": "03 - REGISTRO DE LA PROPIEDAD, HIPOTECAS Y ANOTACIONES PREVENTIVAS",
            "04 - LIBROS INA": "04 - LIBROS INA",
            "05 - LIBRO TOMO CONSERVADOR": "05 - LIBRO TOMO CONSERVADOR",
            "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE": "06 - LIBRO DIGITAL PROPIEDAD INMUEBLE",
            "07 - LIBRO DE PRESENTACIONES (DIARIO)": "07 - LIBRO DE PRESENTACIONES (DIARIO)",
            "09 - LIBRO DE INSCRIPCIONES": "09 - LIBRO DE INSCRIPCIONES",
            "10 - CERTIFICACIONES MUNICIPALES": "10 - CERTIFICACIONES MUNICIPALES",
            "11 - ANOTACIONES PREVENTIVAS": "11 - ANOTACIONES PREVENTIVAS",
            "12 - REINSCRIPCIONES": "12 - REINSCRIPCIONES",
            "13 - LIBRO DE SENTENCIAS": "13 - LIBRO DE SENTENCIAS",
            "14 - PROPIEDAD HORIZONTAL": "14 - PROPIEDAD HORIZONTAL",
            "15 - AUXILIAR DE PRENDAS": "15 - AUXILIAR DE PRENDAS",
            "16 - PRENDAS MERCANTILES": "16 - PRENDAS MERCANTILES",
            "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS": "17 - REGISTRO DE CREDITOS DE AVIO Y REFACCIONARIOS",
            "18 - REGISTRO DE AERONAUTICA": "18 - REGISTRO DE AERONAUTICA",
            "21 - REGISTRO COMERCIANTE SOCIAL": "21 - REGISTRO COMERCIANTE SOCIAL",
            "22 - REGISTRO COMERCIANTE INDIVIDUAL": "22 - REGISTRO COMERCIANTE INDIVIDUAL",
            "24 - ESTABLECIMIENTO MERCANTIL": "24 - ESTABLECIMIENTO MERCANTIL",
            "30 - REGISTRO DE PODERES": "30 - REGISTRO DE PODERES",
            "34 - REGISTRO MERCANTIL (ANTIGUO)": "34 - REGISTRO MERCANTIL (ANTIGUO)",
            "35 - LIBRO DE ACTAS DE REGULARIZACION PREDIAL": "35 - LIBRO DE ACTAS DE REGULARIZACION PREDIAL",
            "36 - PERSONERIAS JURIDICAS": "36 - PERSONERIAS JURIDICAS",
            "19 - CIRCUNSCRIPCION": "19 - CIRCUNSCRIPCION",
            "OTROS": "OTROS"
        };

        function actualizarTipoLibro() {
            const select = document.getElementById('tipoLibro');
            const circunscripcion = document.querySelector('input[name="circunscripcion"]:checked')?.value;

            document.querySelectorAll('.radio-label').forEach(label => {
                label.classList.remove('selected');
            });

            if (circunscripcion === 'Nacaome - Valle') {
                document.getElementById('radioNacaome').classList.add('selected');
            } else if (circunscripcion === 'Choluteca - Choluteca') {
                document.getElementById('radioCholuteca').classList.add('selected');
            }

            select.innerHTML = '<option value="">[Seleccionar]</option>';

            if (circunscripcion) {
                const libros = circunscripcion === 'Nacaome - Valle' ? librosNacaome : librosCholuteca;

                Object.entries(libros).forEach(([valor, texto]) => {
                    const option = document.createElement('option');
                    option.value = valor;
                    option.textContent = texto;
                    select.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registroForm');
            const comprobanteInput = document.getElementById('comprobanteInput');

            const fechaInput = document.querySelector('input[name="fecha_recepcion"]');
            fechaInput.max = new Date().toISOString().split('T')[0];

            if (comprobanteInput) {
                comprobanteInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file && file.size > 15 * 1024 * 1024) {
                        alert('El archivo es demasiado grande. Máximo 15MB.');
                        this.value = '';
                    }
                });
            }

            form.addEventListener('submit', function(e) {
                let valid = true;

                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.style.borderColor = '#ef4444';
                        field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.15)';
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('Por favor complete todos los campos obligatorios.');
                }
            });

            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                });
            });
        });

        if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
            document.querySelector('input[type="date"]').addEventListener('focus', function() {
                this.type = 'text';
                this.placeholder = 'DD/MM/AAAA';
                this.addEventListener('blur', function() {
                    if (!this.value) {
                        this.type = 'date';
                    }
                });
            });
        }
    </script>
</x-app-layout>
