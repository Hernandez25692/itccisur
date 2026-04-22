<x-app-layout>
    <div class="training-premium">
        <div class="container-premium">
            <!-- Encabezado con estilo -->
            <div class="header-premium">
                <span class="overline">Gestión de Accesos</span>
                <h2 class="title-premium">Crear <span class="highlight">Validación QR</span></h2>
                <p class="subtitle-premium">Registra una nueva formación para generar códigos de verificación</p>
                <div class="accent-line"></div>
            </div>

            <!-- Tarjeta del formulario (efecto glass) -->
            <div class="form-card">
                @if ($errors->any())
                    <div class="alert-modern alert-error">
                        <i class="fas fa-circle-exclamation"></i>
                        <div>
                            <strong>¡Atención!</strong> Revisa los siguientes errores:
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('validaciones_qr.store') }}" method="POST" class="modern-form">
                    @csrf

                    <div class="form-group-modern">
                        <label for="nombre_formacion">
                            <i class="fas fa-graduation-cap"></i> Nombre de la Formación
                        </label>
                        <input type="text" name="nombre_formacion" id="nombre_formacion"
                            class="form-control-modern @error('nombre_formacion') is-invalid @enderror"
                            value="{{ old('nombre_formacion') }}" placeholder="Ej: Regulaciones Laborales 2026"
                            required>
                        @error('nombre_formacion')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row-modern">
                        <div class="form-group-modern">
                            <label for="fecha_formacion">
                                <i class="fas fa-calendar-alt"></i> Fecha
                            </label>
                            <input type="date" name="fecha_formacion" id="fecha_formacion"
                                class="form-control-modern @error('fecha_formacion') is-invalid @enderror"
                                value="{{ old('fecha_formacion') }}">
                            @error('fecha_formacion')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label for="anio">
                                <i class="fas fa-calendar-week"></i> Año
                            </label>
                            <input type="number" name="anio" id="anio"
                                class="form-control-modern @error('anio') is-invalid @enderror"
                                value="{{ old('anio') }}" placeholder="Ej: 2026" step="1" min="2000"
                                max="2100">
                            @error('anio')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn-premium btn-submit">
                            <i class="fas fa-save"></i> Guardar validación
                        </button>
                        <a href="{{ route('validaciones_qr.index') }}" class="btn-premium btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Estilos modernos (integrados) -->
    <style>
        /* ----- Mismos estilos base de la sección anterior (adaptados) ----- */
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Outfit:wght@400;500;600;700;800&display=swap');

        .training-premium {
            --primary-dark: #0A1927;
            --primary-gold: #D4AF37;
            --primary-gold-dark: #B8902C;
            --card-white: #FFFFFF;
            --text-dark: #1E2A3A;
            --text-light: #5B6E8C;
            --shadow-sm: 0 20px 35px -10px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 25px 40px -12px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .training-premium {
            padding: 4rem 0;
            background: linear-gradient(145deg, #F9FAFE 0%, #F1F4FA 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .container-premium {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Encabezado */
        .header-premium {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.6s ease-out;
        }

        .overline {
            display: inline-block;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
            color: var(--primary-gold);
            background: rgba(212, 175, 55, 0.12);
            padding: 0.3rem 1rem;
            border-radius: 40px;
            margin-bottom: 1rem;
        }

        .title-premium {
            font-size: 2.6rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            color: var(--primary-dark);
            margin-bottom: 0.8rem;
            letter-spacing: -0.02em;
        }

        .highlight {
            background: linear-gradient(135deg, var(--primary-gold), #F5D97A);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .subtitle-premium {
            font-size: 1rem;
            color: var(--text-light);
            max-width: 550px;
            margin: 0 auto;
        }

        .accent-line {
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-dark));
            margin: 1rem auto 0;
            border-radius: 4px;
        }

        /* Tarjeta del formulario */
        .form-card {
            background: var(--card-white);
            border-radius: 32px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(2px);
        }

        .form-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-3px);
        }

        /* Alertas modernas */
        .alert-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fff2f0;
            border-left: 5px solid #e53e3e;
            padding: 1rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            color: #c53030;
        }

        .alert-modern i {
            font-size: 1.4rem;
            margin-top: 2px;
        }

        .alert-modern ul {
            margin: 0.5rem 0 0 1rem;
            padding-left: 0;
        }

        .alert-modern li {
            font-size: 0.85rem;
        }

        /* Formulario */
        .modern-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row-modern {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group-modern {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group-modern label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group-modern label i {
            color: var(--primary-gold);
            width: 20px;
        }

        .form-control-modern {
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            background: #fefefe;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .form-control-modern.is-invalid {
            border-color: #e53e3e;
            background-color: #fff5f5;
        }

        .invalid-feedback-modern {
            color: #e53e3e;
            font-size: 0.75rem;
            margin-top: 0.2rem;
        }

        /* Botones */
        .form-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .btn-premium {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0.75rem 1.8rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            background: var(--primary-dark);
            color: white;
        }

        .btn-premium i {
            font-size: 1rem;
        }

        .btn-submit {
            background: var(--primary-dark);
        }

        .btn-submit:hover {
            background: var(--primary-gold-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .container-premium {
                padding: 0 1rem;
            }

            .title-premium {
                font-size: 2rem;
            }

            .form-row-modern {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-buttons {
                flex-direction: column;
            }

            .btn-premium {
                width: 100%;
                justify-content: center;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- FontAwesome (si no está en tu layout) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
