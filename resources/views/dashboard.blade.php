<x-app-layout>
    <style>
        .welcome-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-wrapper {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(12, 28, 60, 0.08);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #0C1C3C 0%, #1A2A4F 100%);
            padding: 3rem 2.5rem;
            position: relative;
        }

        .accent-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #C5A049, #D4B56C);
        }

        .hero-content {
            text-align: center;
            color: white;
        }

        .hero-title {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Support Section */
        .support-section {
            padding: 3rem 2.5rem;
        }

        .support-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
        }

        .support-info h3 {
            color: #1A2A4F;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .support-text {
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            transition: all 0.2s;
        }

        .contact-box:hover {
            transform: translateX(5px);
            border-color: #C5A049;
        }

        .contact-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .contact-value {
            color: #1A2A4F;
            font-weight: 600;
            font-size: 1.125rem;
        }

        .support-hours {
            background: linear-gradient(135deg, #0C1C3C 0%, #1A2A4F 100%);
            border-radius: 12px;
            padding: 1.75rem;
            color: white;
        }

        .hours-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .support-hours p {
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 1rem;
            }

            .support-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-section,
            .support-section {
                padding: 2rem 1.5rem;
            }

            .hero-title {
                font-size: 1.75rem;
            }
        }
    </style>

    <div class="welcome-container">
        <div class="welcome-wrapper">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="accent-line"></div>
                <div class="hero-content">
                    <h1 class="hero-title">Bienvenido al CCISUR TI</h1>
                    <p class="hero-subtitle">
                        Plataforma institucional del Departamento de Sistemas y Tecnologías de Información
                        de la Cámara de Comercio e Industrias del Sur.
                    </p>
                </div>
            </section>

            <!-- Support Section -->
            <section class="support-section">
                <div class="support-content">
                    <div class="support-info">
                        <h3>
                            <span>📞</span>
                            Soporte Técnico
                        </h3>
                        <p class="support-text">
                            Nuestro equipo de soporte técnico está disponible para asistirte con cualquier pregunta o
                            inconveniente.
                        </p>
                        <div class="contact-list">
                            <div class="contact-box">
                                <div class="contact-label">👨‍💼 Responsable</div>
                                <div class="contact-value">José Hernandez</div>
                            </div>
                            <div class="contact-box">
                                <div class="contact-label">📱 Celular</div>
                                <div class="contact-value">9944-3669</div>
                            </div>
                            <div class="contact-box">
                                <div class="contact-label">☎️ PBX</div>
                                <div class="contact-value">2782-2929 (Ext. 1005)</div>
                            </div>
                            <div class="contact-box">
                                <div class="contact-label">📧 Email</div>
                                <div class="contact-value">it@ccisur.org</div>
                            </div>
                        </div>
                    </div>

                    <div class="support-hours">
                        <div class="hours-title">⏰ Horario de Atención</div>
                        <p>
                            <strong>Lunes a Viernes</strong><br>
                            8:00 AM - 5:00 PM
                        </p>
                        <p style="margin-top: 1rem; opacity: 0.9;">
                            Tiempo promedio de respuesta:<br>
                            <strong>Menos de 2 horas hábiles</strong>
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
