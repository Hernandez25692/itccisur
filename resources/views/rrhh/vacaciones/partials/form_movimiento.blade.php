@php
    use App\Models\RrhhVacacionesMovimiento;
    use App\Services\Rrhh\VacacionesService;

    $vacaciones = app(VacacionesService::class);
    $tipo = old('tipo_movimiento', $movimiento->tipo_movimiento ?? 'vacacion_dias');
    $porHorasGuardado = $movimiento->exists && $movimiento->esPorHoras();

    // En edición, la cantidad manual solo se precarga si difiere del cálculo automático
    $diasManual = '';
    $horasManual = '';

    if ($movimiento->exists) {
        if ($movimiento->tipo_movimiento === 'ajuste_manual') {
            $diasManual = VacacionesService::numero(abs((float) $movimiento->dias_equivalentes));
        } else {
            $auto = $vacaciones->calcularCantidades($movimiento->tipo_movimiento, [
                'fecha_inicio' => $movimiento->fecha_inicio->toDateString(),
                'fecha_fin' => $movimiento->fecha_fin?->toDateString(),
                'hora_inicio' => $movimiento->horaInicioCorta(),
                'hora_fin' => $movimiento->horaFinCorta(),
            ]);

            if ($porHorasGuardado && abs($auto['horas'] - (float) $movimiento->horas_equivalentes) > 0.001) {
                $horasManual = VacacionesService::numero($movimiento->horas_equivalentes);
            } elseif (! $porHorasGuardado && abs($auto['dias'] - (float) $movimiento->dias_equivalentes) > 0.001) {
                $diasManual = VacacionesService::numero($movimiento->dias_equivalentes);
            }
        }
    }

    // Saldo sin este movimiento (en edición se devuelve lo que ya descontaba)
    $saldoBase = $estado->saldo
        + ($movimiento->exists && $movimiento->estado === 'aprobado' ? $vacaciones->diasADescontar($movimiento) : 0);

    $siguienteAcreditacion = $vacaciones->proximaAcreditacion($empleado);

    $configuracionJs = [
        'jornada' => $jornada,
        'noDescontables' => $jornada['dias_no_descontables'] ?? [0],
        'saldo' => round($saldoBase, 4),
        'acreditacion' => [
            'fecha' => $siguienteAcreditacion['fecha']->format('d-m-Y'),
            'dias' => $siguienteAcreditacion['dias'],
        ],
    ];
@endphp

{{-- SECCIÓN 1: Tipo de movimiento --}}
<div class="vac-section">
    <div class="vac-section-label">Tipo de solicitud</div>
    <div class="vac-grid-2">
        <div class="vac-field">
            <label class="vac-label" for="tipo_movimiento">
                Seleccione el tipo <span class="req">*</span>
            </label>
            <select name="tipo_movimiento" id="tipo_movimiento"
                class="vac-select @error('tipo_movimiento') is-invalid @enderror">
                @foreach (RrhhVacacionesMovimiento::TIPOS as $valor => $texto)
                    <option value="{{ $valor }}" @selected($tipo === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
            <p class="vac-hint" id="ayuda-tipo"></p>
        </div>

        <div class="vac-field" id="campo-operacion">
            <label class="vac-label" for="operacion">Operación <span class="req">*</span></label>
            <select name="operacion" id="operacion" class="vac-select @error('operacion') is-invalid @enderror">
                <option value="sumar" @selected(old('operacion', (float) $movimiento->dias_equivalentes < 0 ? 'restar' : 'sumar') === 'sumar')>
                    Sumar días al saldo
                </option>
                <option value="restar" @selected(old('operacion', (float) $movimiento->dias_equivalentes < 0 ? 'restar' : 'sumar') === 'restar')>
                    Restar días del saldo
                </option>
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
                <span id="label-fecha-inicio">Fecha inicio</span> <span class="req">*</span>
            </label>
            <input type="date" name="fecha_inicio" id="fecha_inicio"
                class="vac-input @error('fecha_inicio') is-invalid @enderror"
                min="{{ $empleado->fecha_contratacion->format('Y-m-d') }}"
                value="{{ old('fecha_inicio', $movimiento->fecha_inicio?->format('Y-m-d')) }}" required>
            @error('fecha_inicio')
                <span class="vac-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="vac-field" id="campo-fecha-fin">
            <label class="vac-label" for="fecha_fin">
                Fecha fin <span class="req" id="req-fecha-fin">*</span>
            </label>
            <input type="date" name="fecha_fin" id="fecha_fin"
                class="vac-input @error('fecha_fin') is-invalid @enderror"
                value="{{ old('fecha_fin', $movimiento->fecha_fin?->format('Y-m-d')) }}">
            @error('fecha_fin')
                <span class="vac-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="vac-grid-2" id="campos-horas" style="margin-top: 1.25rem;">
        <div class="vac-field">
            <label class="vac-label" for="hora_inicio">
                Hora inicio <span class="req" id="req-hora-inicio">*</span>
            </label>
            <input type="time" name="hora_inicio" id="hora_inicio"
                class="vac-input @error('hora_inicio') is-invalid @enderror"
                value="{{ old('hora_inicio', $movimiento->horaInicioCorta()) }}">
            @error('hora_inicio')
                <span class="vac-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="vac-field">
            <label class="vac-label" for="hora_fin">
                Hora fin <span class="req" id="req-hora-fin">*</span>
            </label>
            <input type="time" name="hora_fin" id="hora_fin"
                class="vac-input @error('hora_fin') is-invalid @enderror"
                value="{{ old('hora_fin', $movimiento->horaFinCorta()) }}">
            @error('hora_fin')
                <span class="vac-error">{{ $message }}</span>
            @enderror
        </div>

        <p class="vac-hint span-2" style="grid-column: 1 / -1;">
            Jornada: {{ $jornada['entrada'] }}–{{ $jornada['almuerzo_inicio'] }} y
            {{ $jornada['almuerzo_fin'] }}–{{ $jornada['salida'] }}. La hora de almuerzo y el tiempo fuera de la
            jornada no se descuentan. <span id="ayuda-horas-permiso">En permisos especiales, deje las horas vacías
                para registrar días completos.</span>
        </p>
    </div>
</div>

{{-- SECCIÓN 3: Detalles --}}
<div class="vac-section">
    <div class="vac-section-label">Detalles de la solicitud</div>
    <div class="vac-grid-1">
        <div class="vac-field">
            <label class="vac-label" for="motivo">
                Motivo <span class="req" id="req-motivo">*</span>
            </label>
            <textarea name="motivo" id="motivo" class="vac-textarea @error('motivo') is-invalid @enderror"
                placeholder="Describa el motivo de la solicitud...">{{ old('motivo', $movimiento->motivo) }}</textarea>
            @error('motivo')
                <span class="vac-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="vac-field">
            <label class="vac-label" for="archivo">Comprobante (opcional)</label>
            <input type="file" name="archivo" id="archivo" accept=".pdf,.jpg,.jpeg,.png"
                class="vac-input @error('archivo') is-invalid @enderror">
            @error('archivo')
                <span class="vac-error">{{ $message }}</span>
            @enderror
            <p class="vac-hint">
                PDF, JPG o PNG (máx. 2 MB).
                @if ($movimiento->exists && $movimiento->archivo_comprobante)
                    Actual: <a href="{{ route('vacaciones.comprobante', $movimiento) }}" target="_blank">ver comprobante</a>
                    (si adjunta otro, lo reemplaza).
                @endif
            </p>
        </div>
    </div>
</div>

{{-- Resumen --}}
<div class="vac-alert">
    <div class="vac-alert-title">Resumen de solicitud</div>
    <div class="vac-summary" id="resultadoSolicitud">
        Seleccione fechas u horas para calcular.
    </div>
</div>

{{-- Cantidad manual --}}
<div class="vac-adjustment">
    <div class="vac-grid-2">
        <div class="vac-field" id="campo-dias-manual">
            <label class="vac-label" for="dias_manual" id="label-dias-manual">
                Días a descontar (ajuste manual)
            </label>
            <input type="number" name="dias_manual" id="dias_manual" step="0.25" min="0"
                class="vac-input @error('dias_manual') is-invalid @enderror" value="{{ old('dias_manual', $diasManual) }}"
                placeholder="Opcional — reemplaza el cálculo automático">
            @error('dias_manual')
                <span class="vac-error">{{ $message }}</span>
            @enderror
            <p class="vac-hint" id="ayuda-dias-manual">Úselo solo si debe corregir el cálculo (por ejemplo, un feriado).</p>
        </div>

        <div class="vac-field" id="campo-horas-manual">
            <label class="vac-label" for="horas_manual">Horas a descontar (ajuste manual)</label>
            <input type="number" name="horas_manual" id="horas_manual" step="0.25" min="0" max="24"
                class="vac-input @error('horas_manual') is-invalid @enderror"
                value="{{ old('horas_manual', $horasManual) }}" placeholder="Opcional — reemplaza el cálculo automático">
            @error('horas_manual')
                <span class="vac-error">{{ $message }}</span>
            @enderror
            <p class="vac-hint">8 horas equivalen a 1 día.</p>
        </div>
    </div>
</div>

{{-- Vacaciones adelantadas: se permiten, solo se informa --}}
<div class="vac-alert" id="aviso-saldo" data-forzar="0" style="display:none;">
    <div class="vac-alert-title">Quedará como adelanto</div>
    <p id="aviso-saldo-texto" style="margin: 0;"></p>
</div>

<script>
    (function() {
        const cfg = @json($configuracionJs);

        const $ = (id) => document.getElementById(id);
        const tipo = $('tipo_movimiento');
        const fi = $('fecha_inicio');
        const ff = $('fecha_fin');
        const hi = $('hora_inicio');
        const hf = $('hora_fin');
        const dm = $('dias_manual');
        const hm = $('horas_manual');
        const op = $('operacion');
        const salida = $('resultadoSolicitud');
        const aviso = $('aviso-saldo');

        const ayudas = {
            vacacion_dias: 'Descuenta días del saldo. Los domingos no se cuentan; los sábados sí.',
            vacacion_horas: 'Descuenta horas del saldo (8 horas = 1 día). Debe ser dentro de un mismo día.',
            permiso_especial: 'Queda registrado en el historial pero NO descuenta vacaciones.',
            ajuste_manual: 'Corrección administrativa del saldo. Indique la cantidad y el motivo.',
        };

        const minutos = (hora) => {
            const [h, m] = hora.split(':').map(Number);
            return h * 60 + (m || 0);
        };

        const bloques = [
            [minutos(cfg.jornada.entrada), minutos(cfg.jornada.almuerzo_inicio)],
            [minutos(cfg.jornada.almuerzo_fin), minutos(cfg.jornada.salida)],
        ];

        const horasDia = bloques.reduce((total, [desde, hasta]) => total + (hasta - desde), 0) / 60;

        // Fechas en UTC para que el día de la semana no cambie por la zona horaria
        const dia = (valor) => {
            const [y, m, d] = valor.split('-').map(Number);
            return Date.UTC(y, m - 1, d);
        };

        const fmt = (n) => (Math.round(n * 100) / 100).toLocaleString('es-HN', {
            maximumFractionDigits: 2
        });

        function diasDescontables(inicio, fin) {
            let dias = 0;
            for (let t = dia(inicio); t <= dia(fin); t += 86400000) {
                if (!cfg.noDescontables.includes(new Date(t).getUTCDay())) {
                    dias++;
                }
            }
            return dias;
        }

        function horasDescontables(inicio, fin) {
            const a = minutos(inicio);
            const b = minutos(fin);
            let total = 0;
            bloques.forEach(([desde, hasta]) => {
                total += Math.max(0, Math.min(b, hasta) - Math.max(a, desde));
            });
            return Math.round(total / 60 * 100) / 100;
        }

        const porHoras = () => tipo.value === 'vacacion_horas' ||
            (tipo.value === 'permiso_especial' && hi.value !== '' && hf.value !== '');

        // Los campos ocultos se desactivan para que no se envíen
        const mostrar = (id, visible) => {
            $(id).style.display = visible ? '' : 'none';
            $(id).querySelectorAll('input, select, textarea').forEach((campo) => {
                campo.disabled = !visible;
            });
        };

        function actualizarCampos() {
            const t = tipo.value;

            mostrar('campo-operacion', t === 'ajuste_manual');
            mostrar('campo-fecha-fin', t === 'vacacion_dias' || t === 'permiso_especial');
            mostrar('campos-horas', t === 'vacacion_horas' || t === 'permiso_especial');
            mostrar('ayuda-horas-permiso', t === 'permiso_especial');
            mostrar('campo-dias-manual', t !== 'vacacion_horas' && !(t === 'permiso_especial' && porHoras()));
            mostrar('campo-horas-manual', porHoras());

            $('req-fecha-fin').style.display = t === 'vacacion_dias' ? '' : 'none';
            $('req-hora-inicio').style.display = t === 'vacacion_horas' ? '' : 'none';
            $('req-hora-fin').style.display = t === 'vacacion_horas' ? '' : 'none';
            $('req-motivo').style.display = t === 'ajuste_manual' ? '' : 'none';
            $('label-fecha-inicio').textContent = (t === 'vacacion_dias' || t === 'permiso_especial') ?
                'Fecha inicio' : 'Fecha';
            $('ayuda-tipo').textContent = ayudas[t] || '';

            if (t === 'ajuste_manual') {
                $('label-dias-manual').textContent = 'Cantidad de días *';
                $('ayuda-dias-manual').textContent = 'Días a sumar o restar del saldo (admite decimales, 0.5 = medio día).';
                dm.placeholder = 'Ej. 2';
            } else {
                $('label-dias-manual').textContent = t === 'permiso_especial' ?
                    'Días del permiso (ajuste manual)' : 'Días a descontar (ajuste manual)';
                $('ayuda-dias-manual').textContent = 'Úselo solo si debe corregir el cálculo (por ejemplo, un feriado).';
                dm.placeholder = 'Opcional — reemplaza el cálculo automático';
            }

            calcular();
        }

        function avisar(descuento) {
            const queda = cfg.saldo - descuento;

            if (descuento > 0 && queda < -0.0001) {
                aviso.style.display = '';
                $('aviso-saldo-texto').innerHTML = 'Disponible hoy: <strong>' + fmt(cfg.saldo) +
                    '</strong> día(s). Con esta solicitud el saldo quedará en <strong>' + fmt(queda) +
                    '</strong> y se completará con la acreditación del ' + cfg.acreditacion.fecha +
                    ' (+' + cfg.acreditacion.dias + ' días).';
            } else {
                aviso.style.display = 'none';
            }
        }

        function calcular() {
            const t = tipo.value;

            if (t === 'ajuste_manual') {
                const d = parseFloat(dm.value);
                if (!d) {
                    salida.innerHTML = 'Indique la cantidad de días del ajuste.';
                    return avisar(0);
                }
                const restar = op.value === 'restar';
                salida.innerHTML = (restar ? 'Se restarán ' : 'Se sumarán ') + '<strong>' + fmt(d) +
                    '</strong> día(s) ' + (restar ? 'del' : 'al') + ' saldo. Saldo resultante: <strong>' +
                    fmt(cfg.saldo + (restar ? -d : d)) + '</strong>.';
                return avisar(restar ? d : 0);
            }

            if (!fi.value) {
                salida.innerHTML = 'Seleccione la fecha de inicio.';
                return avisar(0);
            }

            let dias = 0;
            let texto = '';

            if (porHoras()) {
                if (!hi.value || !hf.value) {
                    salida.innerHTML = 'Indique la hora de inicio y de fin.';
                    return avisar(0);
                }
                if (minutos(hf.value) <= minutos(hi.value)) {
                    salida.innerHTML = 'La hora de fin debe ser posterior a la hora de inicio.';
                    return avisar(0);
                }

                const auto = horasDescontables(hi.value, hf.value);
                const horas = hm.value !== '' ? parseFloat(hm.value) : auto;
                const reloj = (minutos(hf.value) - minutos(hi.value)) / 60;

                dias = horas / horasDia;
                texto = 'Horas a descontar: <strong>' + fmt(horas) + ' h</strong> (equivale a ' + fmt(dias) +
                    ' día)';

                if (hm.value !== '') {
                    texto += '<br>Cantidad ajustada manualmente (cálculo automático: ' + fmt(auto) + ' h).';
                } else if (reloj > auto) {
                    texto += '<br>De ' + fmt(reloj) + ' h de reloj se excluyen ' + fmt(reloj - auto) +
                        ' h de almuerzo o fuera de jornada.';
                }
            } else {
                const fin = ff.value || fi.value;

                if (t === 'vacacion_dias' && !ff.value) {
                    salida.innerHTML = 'Seleccione la fecha de fin.';
                    return avisar(0);
                }
                if (dia(fin) < dia(fi.value)) {
                    salida.innerHTML = 'La fecha de fin no puede ser anterior a la fecha de inicio.';
                    return avisar(0);
                }

                const auto = diasDescontables(fi.value, fin);
                const calendario = Math.round((dia(fin) - dia(fi.value)) / 86400000) + 1;

                dias = dm.value !== '' ? parseFloat(dm.value) : auto;
                texto = (t === 'permiso_especial' ? 'Días del permiso: ' : 'Días a descontar: ') + '<strong>' +
                    fmt(dias) + '</strong>';

                if (dm.value !== '') {
                    texto += '<br>Cantidad ajustada manualmente (cálculo automático: ' + fmt(auto) + ').';
                } else if (calendario !== auto) {
                    texto += ' (' + calendario + ' días calendario; no se cuentan ' + (calendario - auto) +
                        ' domingo(s))';
                }
            }

            if (t === 'permiso_especial') {
                texto += '<br>Los permisos especiales quedan registrados pero no descuentan vacaciones.';
                salida.innerHTML = texto;
                return avisar(0);
            }

            texto += '<br>Saldo después de la solicitud: <strong>' + fmt(cfg.saldo - dias) + '</strong> día(s).';
            salida.innerHTML = texto;
            avisar(dias);
        }

        [tipo, op].forEach((el) => el.addEventListener('change', actualizarCampos));
        [hi, hf].forEach((el) => el.addEventListener('input', actualizarCampos));
        [fi, ff, dm, hm].forEach((el) => el.addEventListener('input', calcular));

        fi.addEventListener('change', () => {
            if (ff.value && ff.value < fi.value) {
                ff.value = fi.value;
            }
            if (!ff.value && tipo.value === 'vacacion_dias') {
                ff.min = fi.value;
            }
            calcular();
        });

        actualizarCampos();
    })();
</script>
