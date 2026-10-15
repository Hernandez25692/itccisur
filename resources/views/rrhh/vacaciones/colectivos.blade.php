<x-app-layout>

    @include('rrhh.partials.ui')

    <style>
        .rh-col-section {
            padding: 1.25rem 0 1.5rem;
            border-bottom: 1px solid var(--rule);
        }

        .rh-col-section h2 {
            margin: 0 0 0.35rem;
            font-size: 1.15rem;
            font-weight: 700;
            font-stretch: 80%;
            color: var(--navy);
        }

        .rh-col-section > p {
            margin: 0 0 1rem;
            color: var(--ink-2);
            font-size: 0.875rem;
            max-width: 70ch;
        }

        .rh-sugerencias {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-bottom: 1.1rem;
        }

        .rh-col-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) repeat(3, minmax(0, 1fr));
            gap: 1rem;
            max-width: 62rem;
        }

        .rh-calculo {
            margin-top: 1rem;
            padding: 0.75rem 0.9rem;
            border-radius: 8px;
            background: var(--paper-2);
            font-size: 0.9rem;
            color: var(--ink-2);
            max-width: 62rem;
        }

        .rh-calculo strong {
            color: var(--ink);
        }

        .rh-sel-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem 1rem;
            margin-bottom: 0.75rem;
        }

        .rh-sel-count {
            font-size: 0.875rem;
            color: var(--ink-2);
        }

        .rh-grupo-col {
            margin-top: 1rem;
        }

        .rh-grupo-col legend {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            width: 100%;
            padding: 0 0 0.45rem;
            border-bottom: 1px solid var(--rule-2);
            font-weight: 700;
            font-stretch: 85%;
            color: var(--navy);
        }

        .rh-checklist {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(17rem, 1fr));
            gap: 0 1.5rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .rh-checklist label {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.6rem 0.25rem;
            border-bottom: 1px solid var(--rule);
            cursor: pointer;
        }

        .rh-checklist label:hover {
            background: var(--paper-2);
        }

        .rh input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            margin-top: 0.15rem;
            border-radius: 4px;
            border-color: var(--rule-2);
            color: var(--navy);
            flex-shrink: 0;
        }

        .rh-check-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .rh-check-text strong {
            font-weight: 600;
        }

        .rh-check-saldo {
            font-size: 0.8rem;
            color: var(--ink-3);
        }

        .rh-col-foot {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding-top: 1.25rem;
        }

        @media (max-width: 760px) {
            .rh-col-grid {
                grid-template-columns: 1fr 1fr;
            }

            .rh-col-grid .is-wide {
                grid-column: 1 / -1;
            }

            .rh-col-foot {
                flex-direction: column-reverse;
            }
        }
    </style>

    @php
        $n = fn ($valor) => \App\Services\Rrhh\VacacionesService::formatoDias($valor);
        $seleccion = collect(old('empleados', $grupos->flatten(1)->pluck('id')->all()))->map(fn ($id) => (int) $id)->all();
    @endphp

    <div class="rh">

        <header class="rh-head">
            <div class="rh-head-main">
                <h1 class="rh-title">Días colectivos</h1>
                <p class="rh-sub">
                    Registra el mismo descuento de vacaciones a varios empleados a la vez: Semana Santa, Semana
                    Morazánica, 24 y 31 de diciembre. Cada empleado recibe su propio registro en el historial, que se
                    puede editar o anular por separado.
                </p>
            </div>
            <div class="rh-head-actions">
                <a href="{{ route('vacaciones.index') }}" class="rh-btn is-quiet">Volver a vacaciones</a>
            </div>
        </header>

        @if ($errors->any())
            <div class="rh-notice is-crit" role="alert">
                <span class="rh-notice-text">
                    @include('rrhh.partials.icono', ['i' => 'alerta'])
                    <span>{{ implode(' ', $errors->all()) }}</span>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="rh-notice is-crit" role="alert">
                <span class="rh-notice-text">
                    @include('rrhh.partials.icono', ['i' => 'alerta'])
                    <span>{{ session('error') }} {{ implode('; ', session('omitidos', [])) }}</span>
                </span>
            </div>
        @endif

        <form method="POST" action="{{ route('vacaciones.colectivos.guardar') }}" id="form-colectivo">
            @csrf

            <section class="rh-col-section" aria-labelledby="titulo-fechas">
                <h2 id="titulo-fechas">Qué días se descuentan</h2>
                <p>Los domingos no se descuentan. La Semana Morazánica cambia cada año por decreto; ingrese sus fechas.</p>

                @if ($sugerencias)
                    <div class="rh-sugerencias" aria-label="Fechas sugeridas">
                        @foreach ($sugerencias as $sugerencia)
                            <button type="button" class="rh-btn is-sm js-sugerencia"
                                data-motivo="{{ $sugerencia['motivo'] }}"
                                data-inicio="{{ $sugerencia['inicio']->format('Y-m-d') }}"
                                data-fin="{{ $sugerencia['fin']->format('Y-m-d') }}">
                                {{ $sugerencia['motivo'] }}
                                <span class="rh-count">
                                    {{ $sugerencia['inicio']->equalTo($sugerencia['fin'])
                                        ? $sugerencia['inicio']->translatedFormat('j M')
                                        : $sugerencia['inicio']->translatedFormat('j') . '–' . $sugerencia['fin']->translatedFormat('j M') }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="rh-col-grid">
                    <div class="is-wide">
                        <label class="rh-label" for="motivo">Motivo</label>
                        <input type="text" id="motivo" name="motivo" required maxlength="255"
                            class="rh-input is-plain @error('motivo') is-invalid @enderror"
                            value="{{ old('motivo') }}" placeholder="Ej. Semana Santa 2027">
                        @error('motivo')
                            <span class="rh-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="rh-label" for="fecha_inicio">Desde</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" required
                            class="rh-input is-plain @error('fecha_inicio') is-invalid @enderror"
                            value="{{ old('fecha_inicio') }}">
                    </div>
                    <div>
                        <label class="rh-label" for="fecha_fin">Hasta</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" required
                            class="rh-input is-plain @error('fecha_fin') is-invalid @enderror"
                            value="{{ old('fecha_fin') }}">
                    </div>
                    <div>
                        <label class="rh-label" for="dias_manual">Días a descontar</label>
                        <input type="number" id="dias_manual" name="dias_manual" step="0.25" min="0.25" max="31"
                            class="rh-input is-plain @error('dias_manual') is-invalid @enderror"
                            value="{{ old('dias_manual') }}" placeholder="Automático">
                        <p class="rh-hint">Solo si debe corregir el cálculo.</p>
                    </div>
                </div>

                <div class="rh-calculo" id="calculo" aria-live="polite">Seleccione las fechas para calcular.</div>
            </section>

            <section class="rh-col-section" aria-labelledby="titulo-empleados">
                <h2 id="titulo-empleados">A quiénes se registra</h2>
                <p>
                    Desmarque a quien trabajó esos días. Si a alguien no le alcanza el saldo, queda como adelanto y se
                    compensa con su próxima acreditación.
                </p>

                <div class="rh-sel-bar">
                    <span class="rh-sel-count" id="conteo" aria-live="polite"></span>
                    <button type="button" class="rh-btn is-sm" id="marcar-todos">Seleccionar todos</button>
                    <button type="button" class="rh-btn is-sm is-quiet" id="marcar-ninguno">Ninguno</button>
                </div>

                @forelse ($grupos as $area => $miembros)
                    <fieldset class="rh-grupo-col">
                        <legend>
                            <input type="checkbox" class="js-grupo" aria-label="Seleccionar a todos en {{ $area }}">
                            {{ $area }} <span class="rh-count">{{ $miembros->count() }}</span>
                        </legend>
                        <ul class="rh-checklist">
                            @foreach ($miembros as $empleado)
                                <li>
                                    <label>
                                        <input type="checkbox" name="empleados[]" value="{{ $empleado->id }}"
                                            class="js-empleado" data-saldo="{{ $saldos[$empleado->id] }}"
                                            @checked(in_array($empleado->id, $seleccion, true))>
                                        <span class="rh-check-text">
                                            <strong>{{ $empleado->nombre_completo }}</strong>
                                            <span class="rh-check-saldo">
                                                Saldo actual {{ $n($saldos[$empleado->id]) }}<span class="js-despues"></span>
                                            </span>
                                        </span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </fieldset>
                @empty
                    <div class="rh-empty"><p>No hay empleados activos.</p></div>
                @endforelse
            </section>

            <div class="rh-col-foot">
                <a href="{{ route('vacaciones.index') }}" class="rh-btn">Cancelar</a>
                <button type="submit" class="rh-btn is-primary" id="registrar">Registrar</button>
            </div>
        </form>
    </div>

    <script>
        (function() {
            const noDescontables = @json($jornada['dias_no_descontables'] ?? [0]);
            const $ = (id) => document.getElementById(id);
            const inicio = $('fecha_inicio');
            const fin = $('fecha_fin');
            const manual = $('dias_manual');
            const motivo = $('motivo');
            const empleados = Array.from(document.querySelectorAll('.js-empleado'));

            const dia = (valor) => {
                const [y, m, d] = valor.split('-').map(Number);
                return Date.UTC(y, m - 1, d);
            };

            const fmt = (n) => (Math.round(n * 100) / 100).toLocaleString('es-HN', {
                maximumFractionDigits: 2
            });

            function diasCalculados() {
                if (!inicio.value || !fin.value || dia(fin.value) < dia(inicio.value)) return null;
                let dias = 0;
                for (let t = dia(inicio.value); t <= dia(fin.value); t += 86400000) {
                    if (!noDescontables.includes(new Date(t).getUTCDay())) dias++;
                }
                return dias;
            }

            function actualizar() {
                const auto = diasCalculados();
                const dias = manual.value !== '' ? parseFloat(manual.value) : auto;
                const marcados = empleados.filter((e) => e.checked);

                if (auto === null) {
                    $('calculo').textContent = 'Seleccione las fechas para calcular.';
                } else {
                    $('calculo').innerHTML = 'Se descontarán <strong>' + fmt(dias || 0) + ' día(s)</strong> a cada empleado seleccionado' +
                        (manual.value !== '' ? ' (cálculo automático: ' + fmt(auto) + ').' : '.');
                }

                empleados.forEach((e) => {
                    const destino = e.closest('label').querySelector('.js-despues');
                    destino.textContent = e.checked && dias ? ', después ' + fmt(parseFloat(e.dataset.saldo) - dias) + ' d' : '';
                });

                document.querySelectorAll('.js-grupo').forEach((grupo) => {
                    const miembros = Array.from(grupo.closest('fieldset').querySelectorAll('.js-empleado'));
                    const activos = miembros.filter((e) => e.checked).length;
                    grupo.checked = activos === miembros.length;
                    grupo.indeterminate = activos > 0 && activos < miembros.length;
                });

                $('conteo').textContent = marcados.length + ' de ' + empleados.length + ' empleados seleccionados';
                $('registrar').textContent = 'Registrar a ' + marcados.length + (marcados.length === 1 ? ' empleado' : ' empleados');
                $('registrar').disabled = marcados.length === 0;
            }

            document.querySelectorAll('.js-sugerencia').forEach((boton) => {
                boton.addEventListener('click', () => {
                    motivo.value = boton.dataset.motivo;
                    inicio.value = boton.dataset.inicio;
                    fin.value = boton.dataset.fin;
                    manual.value = '';
                    actualizar();
                });
            });

            document.querySelectorAll('.js-grupo').forEach((grupo) => {
                grupo.addEventListener('change', () => {
                    grupo.closest('fieldset').querySelectorAll('.js-empleado').forEach((e) => e.checked = grupo.checked);
                    actualizar();
                });
            });

            $('marcar-todos').addEventListener('click', () => {
                empleados.forEach((e) => e.checked = true);
                actualizar();
            });

            $('marcar-ninguno').addEventListener('click', () => {
                empleados.forEach((e) => e.checked = false);
                actualizar();
            });

            inicio.addEventListener('change', () => {
                if (!fin.value || fin.value < inicio.value) fin.value = inicio.value;
                actualizar();
            });

            [fin, manual].forEach((campo) => campo.addEventListener('input', actualizar));
            empleados.forEach((e) => e.addEventListener('change', actualizar));

            actualizar();
        })();
    </script>

</x-app-layout>
