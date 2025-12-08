<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CCISUR TI - Sistema de Tecnologías de Información</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|instrument-sans:400,500,600"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --primary-dark: #0c1c3c;
            --primary-medium: #1a2a4f;
            --primary-light: #23356d;
            --gold-primary: #b79a37;
            --gold-light: #d4b65c;
            --gold-dark: #9c8430;
            --text-light: #f0f4f8;
            --text-muted: #a0aec0;
            --text-dark: #1a202c;
            --glass-bg: rgba(12, 28, 60, 0.85);
            --glass-border: rgba(183, 154, 55, 0.2);
            --success: #38a169;
            --warning: #ecc94b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Instrument Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%);
            color: var(--text-light);
            overflow-x: hidden;
            position: relative;
        }

        /* Fondo tecnológico */
        .background-tech {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .tech-grid {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 40s linear infinite;
            opacity: 0.3;
        }

        @keyframes gridMove {
            0% {
                transform: translateY(0) translateX(0);
            }

            100% {
                transform: translateY(25px) translateX(25px);
            }
        }

        .circuit-lines {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(183, 154, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(183, 154, 55, 0.1) 0%, transparent 50%);
            opacity: 0.3;
        }

        .data-stream {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .data-particle {
            position: absolute;
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, transparent, var(--gold-light), transparent);
            opacity: 0;
            animation: dataFlow 5s linear infinite;
        }

        @keyframes dataFlow {
            0% {
                transform: translateY(-100px);
                opacity: 0;
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.6;
            }

            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        /* Header */
        .header-container {
            width: 100%;
            padding: 1.5rem 2rem;
            backdrop-filter: blur(10px);
            background: var(--glass-bg);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(183, 154, 55, 0.3);
        }

        .logo-icon i {
            font-size: 24px;
            color: var(--primary-dark);
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--text-light), var(--gold-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 0.5px;
        }

        .logo-text .subtitle {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Navegación */
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--gold-light);
            background: rgba(183, 154, 55, 0.1);
        }

        .nav-link.active {
            color: var(--gold-light);
            background: rgba(183, 154, 55, 0.15);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--gold-primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-login {
            color: var(--gold-light);
            border: 1px solid var(--glass-border);
            background: rgba(183, 154, 55, 0.1);
        }

        .btn-login:hover {
            background: rgba(183, 154, 55, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(183, 154, 55, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            color: var(--primary-dark);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(183, 154, 55, 0.4);
        }

        /* Contenido principal */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            position: relative;
            z-index: 10;
        }

        /* Hero Section */
        .hero-section {
            text-align: center;
            padding: 4rem 0;
            margin-bottom: 4rem;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(183, 154, 55, 0.15);
            border: 1px solid var(--glass-border);
            color: var(--gold-light);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            background: linear-gradient(to right, var(--text-light), var(--gold-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-lg {
            padding: 1rem 2.5rem;
            font-size: 1rem;
        }

        /* Tarjetas de características */
        .features-section {
            margin: 6rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .section-title p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3), 0 0 0 1px var(--glass-border);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(183, 154, 55, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .feature-icon i {
            font-size: 24px;
            color: var(--gold-primary);
        }

        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .feature-list {
            list-style: none;
            margin-top: 1rem;
        }

        .feature-list li {
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feature-list li::before {
            content: '✓';
            color: var(--gold-primary);
            font-weight: bold;
        }

        /* Panel de estadísticas */
        .stats-section {
            background: rgba(12, 28, 60, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 3rem;
            margin: 6rem 0;
            backdrop-filter: blur(10px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gold-light);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Footer */
        .footer {
            margin-top: 6rem;
            padding-top: 3rem;
            border-top: 1px solid var(--glass-border);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--gold-light);
        }

        .footer-links a i {
            font-size: 0.8rem;
            width: 16px;
        }

        .contact-info {
            color: var(--text-muted);
            line-height: 1.6;
        }

        .contact-info p {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .contact-info i {
            color: var(--gold-primary);
            width: 20px;
        }

        .copyright {
            text-align: center;
            padding: 2rem 0;
            color: var(--text-muted);
            font-size: 0.9rem;
            border-top: 1px solid var(--glass-border);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-cta {
                flex-direction: column;
                align-items: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .main-container {
                padding: 2rem 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .auth-buttons {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 2rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }
        }

        /* Animaciones de entrada */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }
    </style>
</head>

<body>
    <!-- Fondo tecnológico -->
    <div class="background-tech">
        <div class="tech-grid"></div>
        <div class="circuit-lines"></div>
        <div class="data-stream" id="dataStream"></div>
    </div>

    <!-- Header -->
    <header class="header-container fade-in">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="logo-text">
                    <h1>CCISUR TI</h1>
                    <div class="subtitle">Departamento de Sistemas</div>
                </div>
            </div>

            

            <div class="auth-buttons">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    
                @endauth
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="main-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-badge fade-in">
                SISTEMA INSTITUCIONAL DE TECNOLOGÍAS
            </div>
            <h1 class="hero-title fade-in delay-1">
                Plataforma Digital <br>para la Gestión Empresarial
            </h1>
            <p class="hero-subtitle fade-in delay-2">
                Soluciones tecnológicas avanzadas para la Cámara de Comercio e Industrias del Sur.
                Un ecosistema integral que optimiza procesos, garantiza seguridad y potencia la innovación
                institucional.
            </p>
            <div class="hero-cta fade-in delay-3">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket"></i> Acceder al Sistema
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="main-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>CCISUR TI</h3>
                    <div class="contact-info">
                        <p><i class="fas fa-building"></i> Departamento de Sistemas</p>
                        <p><i class="fas fa-location-dot"></i> Cámara de Comercio e Industrias del Sur</p>
                        <p><i class="fas fa-phone"></i> Soporte: +51 123 456 789</p>
                        <p><i class="fas fa-envelope"></i> soporte@ccisur.org</p>
                    </div>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2023 CCISUR TI - Departamento de Sistemas y Tecnologías de Información. Todos los derechos
                    reservados.</p>
                <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">
                    Sistema v2.4.1 | Última actualización: Diciembre 2025 |
                    <i class="fas fa-shield-check" style="color: var(--success); margin-left: 1rem;"></i> Sistema
                    Seguro
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Crear partículas de datos animadas
        function createDataParticles() {
            const stream = document.getElementById('dataStream');
            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'data-particle';

                // Posición aleatoria
                const left = Math.random() * 100;
                const delay = Math.random() * 5;
                const duration = 4 + Math.random() * 3;
                const height = 20 + Math.random() * 50;

                particle.style.left = `${left}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                particle.style.height = `${height}px`;

                stream.appendChild(particle);
            }
        }

        // Efectos de scroll para animaciones
        function handleScrollAnimations() {
            const elements = document.querySelectorAll('.fade-in');

            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.style.animationPlayState = 'running';
                }
            });
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            createDataParticles();
            handleScrollAnimations();

            // Aplicar animaciones de entrada
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.2}s`;
            });

            // Efecto de scroll
            window.addEventListener('scroll', handleScrollAnimations);

            // Efecto de hover en tarjetas
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Actualizar año en copyright
            const yearSpan = document.querySelector('.copyright p');
            if (yearSpan) {
                yearSpan.innerHTML = yearSpan.innerHTML.replace('2023', new Date().getFullYear());
            }
        });
    </script>
</body>

</html>
