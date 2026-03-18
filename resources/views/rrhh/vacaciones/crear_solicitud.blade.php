<x-app-layout>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap');

        :root {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --border-light: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --gold: #C5A049;
            --gold-light: #d4b56a;
            --gold-dim: rgba(197, 160, 73, 0.1);
            --gold-border: rgba(197, 160, 73, 0.3);
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --success: #10b981;
            --success-light: #d1fae5;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --warning: #f59e0b;
            --error: #ef4444;
        }

        .vac-page {
            min-height: 100vh;
            background: var(--bg-primary);
            font-family: 'DM Sans', sans-serif;
            padding: 2rem 1rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Breadcrumb ── */
        .vac-breadcrumb {
            width: 100%;
            max-width: 800px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 0.78rem;
            color: var(--gray-500);
            letter-spacing: 0.04em;
        }

        .vac-breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
            transition: color .2s;
        }

        .vac-breadcrumb a:hover {
            color: var(--gold);
        }

        .vac-breadcrumb span {
            color: var(--gray-700);
            font-weight: 500;
        }

        /* ── Card ── */
        .vac-card {
            width: 100%;
            max-width: 800px;
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* ── Card Header ── */
        .vac-header {
            padding: 2rem 2.5rem 1.75rem;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(135deg, rgba(197, 160, 73, 0.03) 0%, transparent 60%);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .vac-header-icon {
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

        .vac-header-icon svg {
            width: 22px;
            height: 22px;
            color: var(--gold);
        }

        .vac-header-title {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1.2;
            margin: 0;
        }

        .vac-header-sub {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin: 0.2rem 0 0;
        }

        .vac-employee-name {
            color: var(--gold);
            font-weight: 600;
        }

        /* ── Form Body ── */
        .vac-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        /* ── Section Divider ── */
        .vac-section {
            margin-bottom: 1.75rem;
        }

        .vac-section-label {
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

        .vac-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gold-border);
        }

        /* ── Grid ── */
        .vac-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .vac-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .vac-grid-1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        /* ── Field ── */
        .vac-field {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .vac-field.span-2 {
            grid-column: span 2;
        }

        .vac-field.span-3 {
            grid-column: span 3;
        }

        .vac-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .vac-label .req {
            color: var(--gold);
            font-size: 0.85em;
        }

        .vac-input,
        .vac-select,
        .vac-textarea {
            width: 100%;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 0.6rem;
            padding: 0.65rem 0.9rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--gray-800);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            box-sizing: border-box;
            -webkit-appearance: none;
            appearance: none;
        }

        .vac-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%2364748b' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1.2rem;
            padding-right: 2.5rem;
        }

        .vac-textarea {
            min-height: 80px;
            resize: vertical;
        }

        .vac-input::placeholder,
        .vac-textarea::placeholder {
            color: var(--gray-400);
        }

        .vac-input:hover,
        .vac-select:hover,
        .vac-textarea:hover {
            border-color: var(--gray-300);
        }

        .vac-input:focus,
        .vac-select:focus,
        .vac-textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.12);
        }

        /* Date & time input icon color fix */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(0.4);
            cursor: pointer;
            opacity: 0.7;
        }

        .vac-hint {
            font-size: 0.73rem;
            color: var(--gray-500);
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-top: 0.25rem;
        }

        .vac-hint svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
            color: var(--gray-400);
        }

        /* ── Alert / Resumen ── */
        .vac-alert {
            background: var(--info-light);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .vac-alert-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--info);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .vac-alert-title svg {
            width: 18px;
            height: 18px;
        }

        .vac-alert-content {
            font-size: 1rem;
            color: var(--gray-700);
            line-height: 1.6;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .vac-alert-content strong {
            color: var(--info);
            font-weight: 700;
        }

        /* ── Footer / Actions ── */
        .vac-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            margin-top: 0.5rem;
        }

        .vac-btn-cancel {
            padding: 0.65rem 1.4rem;
            border-radius: 0.6rem;
            border: 1px solid var(--gray-200);
            background: transparent;
            color: var(--gray-600);
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

        .vac-btn-cancel:hover {
            border-color: var(--gray-300);
            color: var(--gray-800);
            background: var(--gray-50);
        }

        .vac-btn-save {
            padding: 0.65rem 1.75rem;
            border-radius: 0.6rem;
            border: none;
            background: linear-gradient(135deg, #d4a843 0%, var(--gold) 60%, #a8862e 100%);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 6px -1px rgba(197, 160, 73, 0.2);
        }

        .vac-btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 10px -1px rgba(197, 160, 73, 0.3);
            filter: brightness(1.05);
        }

        .vac-btn-save:active {
            transform: translateY(0);
        }

        .vac-btn-save svg,
        .vac-btn-cancel svg {
            width: 16px;
            height: 16px;
        }

        /* ── Manual adjustment field ── */
        .vac-adjustment {
            background: var(--gray-50);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin: 1.5rem 0;
            border: 1px solid var(--border-light);
        }

        .vac-adjustment small {
            color: var(--gray-500);
            font-size: 0.75rem;
            display: block;
            margin-top: 0.35rem;
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .vac-header {
                padding: 1.5rem 1.25rem;
            }

            .vac-body {
                padding: 1.5rem 1.25rem 2rem;
            }

            .vac-grid-2,
            .vac-grid-3 {
                grid-template-columns: 1fr;
            }

            .vac-field.span-2,
            .vac-field.span-3 {
                grid-column: span 1;
            }

            .vac-footer {
                flex-direction: column-reverse;
            }

            .vac-btn-cancel,
            .vac-btn-save {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="vac-page">

        {{-- Breadcrumb --}}
        <nav class="vac-breadcrumb">
            <a href="{{ route('dashboard') }}">Inicio</a>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>

            <a href="{{ route('empleados.index') }}">Empleados</a>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>

            @if (isset($empleado) && $empleado->id)
                <a href="{{ route('vacaciones.index', $empleado->id) }}">
                    Control de Vacaciones
                </a>
            @else
                <a href="{{ route('empleados.index') }}">
                    Control de Vacaciones
                </a>
            @endif

            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>

            <span>Registrar vacaciones</span>
        </nav>

        {{-- Card --}}
        <div class="vac-card">

            {{-- Header --}}
            <div class="vac-header">
                <div class="vac-header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        <circle cx="12" cy="15" r="2" />
                    </svg>
                </div>
                <div>
                    <h1 class="vac-header-title">
                        Registrar vacaciones
                    </h1>
                    <p class="vac-header-sub">
                        Para: <span class="vac-employee-name">{{ $empleado->nombre_completo }}</span>
                    </p>
                </div>
            </div>

            {{-- Form --}}
            <div class="vac-body">
                <form method="POST" action="{{ route('vacaciones.guardar') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="empleado_id" value="{{ $empleado->id }}">

                    {{-- SECCIÓN 1: Tipo de movimiento --}}
                    <div class="vac-section">
                        <div class="vac-section-label">Tipo de solicitud</div>
                        <div class="vac-grid-1">
                            <div class="vac-field">
                                <label class="vac-label" for="tipo_movimiento">
                                    Seleccione el tipo <span class="req">*</span>
                                </label>
                                <select name="tipo_movimiento" id="tipo_movimiento" class="vac-select">
                                    <option value="vacacion_dias">Vacaciones por días</option>
                                    <option value="vacacion_horas">Vacaciones por horas</option>
                                    <option value="permiso_especial">Permiso especial</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: Fechas y horas --}}
                    <div class="vac-section">
                        <div class="vac-section-label">Período solicitado</div>
                        <div class="vac-grid-2">
                            <div class="vac-field">
                                <label class="vac-label" for="fecha_inicio">
                                    Fecha inicio <span class="req">*</span>
                                </label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="vac-input">
                            </div>

                            <div class="vac-field">
                                <label class="vac-label" for="fecha_fin">
                                    Fecha fin <span class="req">*</span>
                                </label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="vac-input">
                            </div>

                            <div class="vac-field">
                                <label class="vac-label" for="hora_inicio">
                                    Hora inicio
                                </label>
                                <input type="time" name="hora_inicio" id="hora_inicio" class="vac-input">
                                <p class="vac-hint">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    Solo para vacaciones por horas
                                </p>
                            </div>

                            <div class="vac-field">
                                <label class="vac-label" for="hora_fin">
                                    Hora fin
                                </label>
                                <input type="time" name="hora_fin" id="hora_fin" class="vac-input">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 3: Detalles --}}
                    <div class="vac-section">
                        <div class="vac-section-label">Detalles de la solicitud</div>
                        <div class="vac-grid-1">
                            <div class="vac-field">
                                <label class="vac-label" for="motivo">Motivo</label>
                                <textarea name="motivo" id="motivo" class="vac-textarea" placeholder="Describa el motivo de la solicitud..."></textarea>
                            </div>

                            <div class="vac-field">
                                <label class="vac-label" for="archivo">Comprobante (opcional)</label>
                                <input type="file" name="archivo" id="archivo" class="vac-input">
                                <p class="vac-hint">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 16v-4m0 0v-4m0 4h4m-4 0H8" />
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                    PDF, JPG o PNG (máx. 2MB)
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Resumen --}}
                    <div class="vac-alert">
                        <div class="vac-alert-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                            Resumen de solicitud
                        </div>
                        <div class="vac-alert-content" id="resultadoSolicitud">
                            Seleccione fechas u horas para calcular.
                        </div>
                    </div>

                    {{-- Ajuste manual --}}
                    <div class="vac-adjustment">
                        <div class="vac-field">
                            <label class="vac-label" for="dias_ajuste">
                                Días a descontar (ajuste manual)
                            </label>
                            <input type="number" name="dias_ajuste" id="dias_ajuste" step="0.25"
                                class="vac-input"
                                placeholder="Opcional - solo si desea modificar el cálculo automático">
                            <small>
                                Si se llena este campo, reemplaza el cálculo automático.
                            </small>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="vac-footer">
                        <a href="{{ route('empleados.show', $empleado->id) }}" class="vac-btn-cancel">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="vac-btn-save">
                            <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Guardar solicitud
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script>
        function calcularSolicitud() {
            let tipo = document.getElementById('tipo_movimiento').value;
            let fechaInicio = document.getElementById('fecha_inicio').value;
            let fechaFin = document.getElementById('fecha_fin').value;
            let horaInicio = document.getElementById('hora_inicio').value;
            let horaFin = document.getElementById('hora_fin').value;
            let resultado = document.getElementById('resultadoSolicitud');

            if (tipo === "vacacion_dias") {
                if (fechaInicio && fechaFin) {
                    let inicio = new Date(fechaInicio);
                    let fin = new Date(fechaFin);
                    let diferencia = (fin - inicio) / (1000 * 60 * 60 * 24) + 1;

                    if (diferencia > 0) {
                        resultado.innerHTML = "Días solicitados: <strong>" + diferencia + "</strong>";
                    } else {
                        resultado.innerHTML = "Por favor, verifique las fechas seleccionadas.";
                    }
                } else {
                    resultado.innerHTML = "Complete las fechas de inicio y fin.";
                }
            }

            if (tipo === "vacacion_horas" || tipo === "permiso_especial") {
                if (horaInicio && horaFin && fechaInicio && fechaFin) {
                    // Verificar si es el mismo día
                    if (fechaInicio !== fechaFin) {
                        resultado.innerHTML =
                        "Para solicitudes por horas, la fecha de inicio y fin deben ser el mismo día.";
                        return;
                    }

                    let h1 = horaInicio.split(":");
                    let h2 = horaFin.split(":");

                    let inicio = parseInt(h1[0]) + parseInt(h1[1]) / 60;
                    let fin = parseInt(h2[0]) + parseInt(h2[1]) / 60;

                    let horas = fin - inicio;

                    if (horas < 0) {
                        resultado.innerHTML = "La hora de fin debe ser posterior a la hora de inicio.";
                        return;
                    }

                    if (horas > 0) {
                        let dias = horas / 8;
                        resultado.innerHTML =
                            "Horas solicitadas: <strong>" + horas.toFixed(2) + "</strong><br>" +
                            "Equivalente en días: <strong>" + dias.toFixed(2) + "</strong>";
                    } else {
                        resultado.innerHTML = "Por favor, verifique las horas seleccionadas.";
                    }
                } else {
                    resultado.innerHTML = "Complete las fechas y horas para calcular.";
                }
            }
        }

        // Event listeners
        document.getElementById('fecha_inicio').addEventListener('change', calcularSolicitud);
        document.getElementById('fecha_fin').addEventListener('change', calcularSolicitud);
        document.getElementById('hora_inicio').addEventListener('change', calcularSolicitud);
        document.getElementById('hora_fin').addEventListener('change', calcularSolicitud);
        document.getElementById('tipo_movimiento').addEventListener('change', calcularSolicitud);

        // Validación adicional para fechas
        document.getElementById('fecha_fin').addEventListener('change', function() {
            let inicio = document.getElementById('fecha_inicio').value;
            let fin = this.value;

            if (inicio && fin && fin < inicio) {
                alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
                this.value = inicio;
            }
        });
    </script>

</x-app-layout>
