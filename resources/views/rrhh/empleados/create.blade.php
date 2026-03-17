<x-app-layout>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap');

        :root {
            --navy-950: #080f1e;
            --navy-900: #0f172a;
            --navy-800: #1e293b;
            --navy-700: #273549;
            --navy-600: #334155;
            --gold: #C5A049;
            --gold-light: #d4b56a;
            --gold-dim: rgba(197, 160, 73, 0.15);
            --gold-border: rgba(197, 160, 73, 0.3);
            --white: #ffffff;
            --gray-100: #f1f5f9;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --error: #ef4444;
        }

        .emp-page {
            min-height: 100vh;
            background: var(--navy-900);
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(197, 160, 73, 0.08) 0%, transparent 60%),
                linear-gradient(180deg, var(--navy-950) 0%, var(--navy-900) 40%, var(--navy-800) 100%);
            font-family: 'DM Sans', sans-serif;
            padding: 2rem 1rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Breadcrumb ── */
        .emp-breadcrumb {
            width: 100%;
            max-width: 680px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 0.78rem;
            color: var(--gray-400);
            letter-spacing: 0.04em;
        }

        .emp-breadcrumb a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color .2s;
        }

        .emp-breadcrumb a:hover {
            color: var(--gold-light);
        }

        .emp-breadcrumb span {
            color: var(--gold);
        }

        /* ── Card ── */
        .emp-card {
            width: 100%;
            max-width: 680px;
            background: var(--navy-800);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(197, 160, 73, 0.08),
                0 32px 64px rgba(0, 0, 0, 0.45);
        }

        /* ── Card Header ── */
        .emp-header {
            padding: 2rem 2.5rem 1.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: linear-gradient(135deg, rgba(197, 160, 73, 0.06) 0%, transparent 60%);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .emp-header-icon {
            width: 48px;
            height: 48px;
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .emp-header-icon svg {
            width: 22px;
            height: 22px;
            color: var(--gold);
        }

        .emp-header-title {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.2;
            margin: 0;
        }

        .emp-header-sub {
            font-size: 0.8rem;
            color: var(--gray-400);
            margin: 0.2rem 0 0;
        }

        /* ── Form Body ── */
        .emp-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        /* ── Section Divider ── */
        .emp-section {
            margin-bottom: 1.75rem;
        }

        .emp-section-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .emp-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gold-border);
        }

        /* ── Grid ── */
        .emp-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .emp-grid-1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        /* ── Field ── */
        .emp-field {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .emp-field.span-2 {
            grid-column: span 2;
        }

        .emp-label {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--gray-400);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .emp-label .req {
            color: var(--gold);
            font-size: 0.85em;
        }

        .emp-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.6rem;
            padding: 0.65rem 0.9rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--white);
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
            box-sizing: border-box;
            -webkit-appearance: none;
            appearance: none;
        }

        .emp-input::placeholder {
            color: var(--gray-500);
        }

        .emp-input:hover {
            border-color: rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.06);
        }

        .emp-input:focus {
            border-color: var(--gold);
            background: rgba(197, 160, 73, 0.05);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.12);
        }

        /* Date input icon color fix */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.6) sepia(1) saturate(3) hue-rotate(5deg);
            cursor: pointer;
            opacity: 0.7;
        }

        .emp-hint {
            font-size: 0.73rem;
            color: var(--gray-500);
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .emp-hint svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
            color: var(--gold);
            opacity: 0.7;
        }

        /* ── Footer / Actions ── */
        .emp-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin-top: 0.5rem;
        }

        .emp-btn-cancel {
            padding: 0.65rem 1.4rem;
            border-radius: 0.6rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: transparent;
            color: var(--gray-400);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .emp-btn-cancel:hover {
            border-color: rgba(255, 255, 255, 0.25);
            color: var(--white);
            background: rgba(255, 255, 255, 0.05);
        }

        .emp-btn-save {
            padding: 0.65rem 1.75rem;
            border-radius: 0.6rem;
            border: none;
            background: linear-gradient(135deg, #d4a843 0%, var(--gold) 60%, #a8862e 100%);
            color: var(--navy-900);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 14px rgba(197, 160, 73, 0.3);
        }

        .emp-btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(197, 160, 73, 0.4);
            filter: brightness(1.05);
        }

        .emp-btn-save:active {
            transform: translateY(0);
        }

        .emp-btn-save svg,
        .emp-btn-cancel svg {
            width: 16px;
            height: 16px;
        }

        /* ── Validation errors (Laravel) ── */
        .emp-error {
            font-size: 0.73rem;
            color: var(--error);
        }

        .emp-input.is-invalid {
            border-color: var(--error);
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .emp-header {
                padding: 1.5rem 1.25rem;
            }

            .emp-body {
                padding: 1.5rem 1.25rem 2rem;
            }

            .emp-grid-2 {
                grid-template-columns: 1fr;
            }

            .emp-field.span-2 {
                grid-column: span 1;
            }

            .emp-footer {
                flex-direction: column-reverse;
            }

            .emp-btn-cancel,
            .emp-btn-save {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="emp-page">

        {{-- Breadcrumb --}}
        <nav class="emp-breadcrumb">
            <a href="{{ route('dashboard') }}">Inicio</a>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <a href="{{ route('empleados.index') }}">Empleados</a>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <span>Nuevo registro</span>
        </nav>

        {{-- Card --}}
        <div class="emp-card">

            {{-- Header --}}
            <div class="emp-header">
                <div class="emp-header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3M13.5 4.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM3 19.5a7.5 7.5 0 0 1 15 0" />
                    </svg>
                </div>
                <div>
                    <h1 class="emp-header-title">Registrar nuevo empleado</h1>
                    <p class="emp-header-sub">Complete todos los campos requeridos para crear el registro.</p>
                </div>
            </div>

            {{-- Form --}}
            <div class="emp-body">
                <form method="POST" action="{{ route('empleados.store') }}">
                    @csrf

                    {{-- SECCIÓN 1: Información personal --}}
                    <div class="emp-section">
                        <div class="emp-section-label">Información personal</div>
                        <div class="emp-grid-2">

                            <div class="emp-field span-2">
                                <label class="emp-label" for="nombre_completo">
                                    Nombre completo <span class="req">*</span>
                                </label>
                                <input id="nombre_completo" type="text" name="nombre_completo"
                                    class="emp-input @error('nombre_completo') is-invalid @enderror"
                                    placeholder="Ej. Juan Antonio López" value="{{ old('nombre_completo') }}" required>
                                @error('nombre_completo')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="emp-field">
                                <label class="emp-label" for="identidad">Identidad</label>
                                <input id="identidad" type="text" name="identidad"
                                    class="emp-input @error('identidad') is-invalid @enderror"
                                    placeholder="0000-0000-00000" value="{{ old('identidad') }}">
                                @error('identidad')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="emp-field">
                                <label class="emp-label" for="correo">Correo electrónico</label>
                                <input id="correo" type="email" name="correo"
                                    class="emp-input @error('correo') is-invalid @enderror"
                                    placeholder="correo@empresa.com" value="{{ old('correo') }}">
                                @error('correo')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="emp-field">
                                <label class="emp-label" for="telefono">Teléfono</label>
                                <input id="telefono" type="text" name="telefono"
                                    class="emp-input @error('telefono') is-invalid @enderror"
                                    placeholder="+504 0000-0000" value="{{ old('telefono') }}">
                                @error('telefono')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECCIÓN 2: Información laboral --}}
                    <div class="emp-section">
                        <div class="emp-section-label">Información laboral</div>
                        <div class="emp-grid-2">

                            <div class="emp-field">
                                <label class="emp-label" for="cargo">Cargo</label>
                                <input id="cargo" type="text" name="cargo"
                                    class="emp-input @error('cargo') is-invalid @enderror"
                                    placeholder="Ej. Analista de sistemas" value="{{ old('cargo') }}">
                                @error('cargo')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="emp-field">
                                <label class="emp-label" for="area">Área / Departamento</label>
                                <input id="area" type="text" name="area"
                                    class="emp-input @error('area') is-invalid @enderror" placeholder="Ej. Tecnología"
                                    value="{{ old('area') }}">
                                @error('area')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="emp-field">
                                <label class="emp-label" for="fecha_contratacion">
                                    Fecha de contratación <span class="req">*</span>
                                </label>
                                <input id="fecha_contratacion" type="date" name="fecha_contratacion"
                                    class="emp-input @error('fecha_contratacion') is-invalid @enderror"
                                    value="{{ old('fecha_contratacion') }}" required>
                                @error('fecha_contratacion')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="emp-field">
                                <label class="emp-label" for="vacaciones_acumuladas">
                                    Vacaciones acumuladas
                                </label>
                                <input id="vacaciones_acumuladas" type="number" step="0.01"
                                    name="vacaciones_acumuladas"
                                    class="emp-input @error('vacaciones_acumuladas') is-invalid @enderror"
                                    placeholder="0.00" value="{{ old('vacaciones_acumuladas', 0) }}">
                                @error('vacaciones_acumuladas')
                                    <span class="emp-error">{{ $message }}</span>
                                @enderror
                                <p class="emp-hint">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 16v-4M12 8h.01" />
                                    </svg>
                                    Días acumulados antes de iniciar en el sistema
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="emp-footer">
                        <a href="{{ route('empleados.index') }}" class="emp-btn-cancel">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="emp-btn-save">
                            <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Guardar empleado
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</x-app-layout>
