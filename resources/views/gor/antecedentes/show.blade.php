<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
        }

        .detail-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .detail-container {
                padding: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .detail-container {
                padding: 2rem;
            }
        }

        .detail-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .detail-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        /* Header */
        .detail-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 2rem 1.5rem;
            color: white;
            text-align: center;
            position: relative;
        }

        @media (min-width: 640px) {
            .detail-header {
                padding: 2.5rem;
            }
        }

        .detail-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
        }

        .detail-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        @media (min-width: 768px) {
            .detail-title {
                font-size: 1.75rem;
            }
        }

        .detail-subtitle {
            opacity: 0.9;
            font-size: 0.875rem;
        }

        /* Body */
        .detail-body {
            padding: 1.5rem;
        }

        @media (min-width: 640px) {
            .detail-body {
                padding: 2rem;
            }
        }

        /* Info Sections */
        .info-section {
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .info-section {
                margin-bottom: 2rem;
            }
        }

        .section-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .section-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            color: #1f2937;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Divider */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 1.5rem 0;
        }

        @media (min-width: 768px) {
            .section-divider {
                margin: 2rem 0;
            }
        }

        /* Motivo Section */
        .motivo-section {
            background: #f9fafb;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .motivo-label {
            color: var(--primary-dark);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .motivo-content {
            color: #4b5563;
            font-size: 0.875rem;
            line-height: 1.6;
            white-space: pre-line;
        }

        /* Comprobante */
        .comprobante-section {
            background: #f9fafb;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .comprobante-label {
            color: var(--primary-dark);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comprobante-image {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .comprobante-image:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .comprobante-link {
            display: block;
            text-decoration: none;
        }

        /* Auditoría */
        .auditoria-section {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 3px solid var(--gold-light);
        }

        .auditoria-title {
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .auditoria-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .auditoria-item:last-child {
            margin-bottom: 0;
        }

        .auditoria-label {
            color: #6b7280;
            font-size: 0.75rem;
        }

        .auditoria-value {
            color: #374151;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Actions */
        .detail-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (min-width: 640px) {
            .detail-actions {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .btn-back {
            color: #6b7280;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-back:hover {
            color: var(--primary-dark);
            background: #f9fafb;
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-light) 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(197, 160, 73, 0.3);
        }

        /* Touch optimizations */
        @media (hover: none) and (pointer: coarse) {

            .btn-back,
            .btn-edit {
                min-height: 44px;
                min-width: 44px;
            }

            .comprobante-image {
                min-height: 150px;
            }
        }

        /* Print styles */
        @media print {
            .detail-container {
                background: white;
                padding: 0;
            }

            .detail-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .btn-back,
            .btn-edit {
                display: none;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .detail-card {
                background: #1f2937;
                border-color: #374151;
            }

            .info-value,
            .motivo-content,
            .auditoria-value {
                color: #e5e7eb;
            }

            .info-label,
            .auditoria-label {
                color: #9ca3af;
            }

            .motivo-section,
            .comprobante-section,
            .auditoria-section {
                background: #374151;
            }

            .section-divider {
                background: linear-gradient(90deg, transparent, #4b5563, transparent);
            }
        }
    </style>

    <div class="detail-container">
        <div class="detail-wrapper">
            <div class="detail-card">
                <!-- Header -->
                <div class="detail-header">
                    <h1 class="detail-title">Revisión de Antecedente Registral</h1>
                    <p class="detail-subtitle">Centro Asociado del Sur</p>
                </div>

                <!-- Body -->
                <div class="detail-body">
                    <!-- Información Principal -->
                    <div class="info-section">
                        <div class="section-grid">
                            <div class="info-item">
                                <span class="info-label">Circunscripción</span>
                                <span class="info-value">{{ $registro->circunscripcion }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Fecha de Recepción</span>
                                <span class="info-value">
                                    {{ \Carbon\Carbon::parse($registro->fecha_recepcion)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Datos del Solicitante -->
                    <div class="info-section">
                        <div class="section-grid">
                            <div class="info-item">
                                <span class="info-label">Solicitante</span>
                                <span class="info-value">{{ $registro->solicitante_nombre }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Dirección</span>
                                <span class="info-value">{{ $registro->solicitante_direccion }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">N° Exequátur</span>
                                <span class="info-value">{{ $registro->numero_exequatur }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Asiento / Tomo / Matrícula</span>
                                <span class="info-value">{{ $registro->asiento_tomo_matricula }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Tipo de Libro</span>
                                <span class="info-value">{{ $registro->tipo_libro }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Motivo -->
                    <div class="motivo-section">
                        <div class="motivo-label">📝 Motivo de la Solicitud</div>
                        <div class="motivo-content">{{ $registro->motivo }}</div>
                    </div>

                    <!-- Comprobante -->
                    @if ($registro->comprobante_path)
                        <div class="comprobante-section">
                            <div class="comprobante-label">📎 Comprobante Adjunto</div>
                            <a href="{{ asset('storage/' . $registro->comprobante_path) }}" target="_blank"
                                class="comprobante-link">
                                <img src="{{ asset('storage/' . $registro->comprobante_path) }}"
                                    alt="Comprobante del registro" class="comprobante-image">
                            </a>
                        </div>
                    @endif

                    <!-- Auditoría -->
                    <div class="auditoria-section">
                        <div class="auditoria-title">📊 Registro de Auditoría</div>

                        <div class="auditoria-item">
                            <span class="auditoria-label">Creado por</span>
                            <span class="auditoria-value">
                                {{ $registro->creador->name ?? 'N/D' }}
                                ({{ $registro->created_at->format('d/m/Y H:i') }})
                            </span>
                        </div>

                        @if ($registro->editor)
                            <div class="auditoria-item">
                                <span class="auditoria-label">Última modificación</span>
                                <span class="auditoria-value">
                                    {{ $registro->editor->name }}
                                    ({{ $registro->updated_at->format('d/m/Y H:i') }})
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="detail-actions">
                        <a href="{{ route('gor.antecedentes.index') }}" class="btn-back">
                            ← Volver al listado
                        </a>

                        <a href="{{ route('gor.antecedentes.edit', $registro->id) }}" class="btn-edit">
                            ✏️ Editar Registro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox para imagen del comprobante -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const comprobanteImage = document.querySelector('.comprobante-image');

            if (comprobanteImage) {
                comprobanteImage.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Crear modal para vista ampliada
                    const modal = document.createElement('div');
                    modal.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.9);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        padding: 1rem;
                    `;

                    const img = document.createElement('img');
                    img.src = comprobanteImage.src;
                    img.style.cssText = `
                        max-width: 100%;
                        max-height: 100%;
                        border-radius: 8px;
                        object-fit: contain;
                    `;

                    modal.appendChild(img);
                    document.body.appendChild(modal);

                    // Cerrar modal al hacer clic
                    modal.addEventListener('click', function() {
                        document.body.removeChild(modal);
                    });
                });
            }

            // Optimizar carga de imágenes para móviles
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.loading = 'lazy';
            });
        });
    </script>
</x-app-layout>
