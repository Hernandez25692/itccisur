@php
    $empleado = $empleado ?? null;
    $vacaciones = app(\App\Services\Rrhh\VacacionesService::class);
    $venceHistorico = $vacaciones->vencimientoAcumuladoHistorico();
    $mostrarAcumuladas = today()->lte($venceHistorico) || ($empleado && $empleado->vacaciones_acumuladas > 0);
@endphp

@if ($errors->any())
    <div class="emp-alert is-error">
        Revise los campos marcados:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
                placeholder="Ej. Juan Antonio López" value="{{ old('nombre_completo', $empleado?->nombre_completo) }}"
                required>
            @error('nombre_completo')
                <span class="emp-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="emp-field">
            <label class="emp-label" for="identidad">Identidad</label>
            <input id="identidad" type="text" name="identidad"
                class="emp-input @error('identidad') is-invalid @enderror" placeholder="0000-0000-00000"
                value="{{ old('identidad', $empleado?->identidad) }}" maxlength="15" inputmode="numeric"
                pattern="[0-9]{4}-[0-9]{4}-[0-9]{5}" title="Formato: 0000-0000-00000">
            @error('identidad')
                <span class="emp-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="emp-field">
            <label class="emp-label" for="correo">Correo electrónico</label>
            <input id="correo" type="email" name="correo"
                class="emp-input @error('correo') is-invalid @enderror" placeholder="correo@empresa.com"
                value="{{ old('correo', $empleado?->correo) }}">
            @error('correo')
                <span class="emp-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="emp-field">
            <label class="emp-label" for="telefono">Teléfono</label>
            <input id="telefono" type="text" name="telefono"
                class="emp-input @error('telefono') is-invalid @enderror" placeholder="+504 0000-0000"
                value="{{ old('telefono', $empleado?->telefono) }}" maxlength="14" inputmode="numeric"
                pattern="\+504 [0-9]{4}-[0-9]{4}" title="Formato: +504 0000-0000">
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
            <select id="cargo" name="cargo" class="emp-input @error('cargo') is-invalid @enderror">
                <option value="">Seleccione un cargo</option>
                @foreach ($cargos as $cargoOption)
                    <option value="{{ $cargoOption }}" @selected(old('cargo', $empleado?->cargo) === $cargoOption)>
                        {{ $cargoOption }}
                    </option>
                @endforeach
            </select>
            @error('cargo')
                <span class="emp-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="emp-field">
            <label class="emp-label" for="area">Área / Departamento</label>
            <select id="area" name="area" class="emp-input @error('area') is-invalid @enderror">
                <option value="">Seleccione un área</option>
                @foreach ($areas as $areaOption)
                    <option value="{{ $areaOption }}" @selected(old('area', $empleado?->area) === $areaOption)>
                        {{ $areaOption }}
                    </option>
                @endforeach
            </select>
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
                value="{{ old('fecha_contratacion', $empleado?->fecha_contratacion?->format('Y-m-d')) }}" required>
            @error('fecha_contratacion')
                <span class="emp-error">{{ $message }}</span>
            @enderror
            <p class="emp-hint">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4M12 8h.01" />
                </svg>
                @if ($empleado)
                    Si la cambia, se recalculan los periodos y acreditaciones por aniversario.
                @else
                    Los días por ley se acreditan en cada aniversario laboral (10, 12, 15 y 20 días).
                @endif
            </p>
        </div>

        @if ($mostrarAcumuladas)
            <div class="emp-field">
                <label class="emp-label" for="vacaciones_acumuladas">
                    Vacaciones acumuladas (histórico)
                </label>
                <input id="vacaciones_acumuladas" type="number" step="0.01" min="0" max="999"
                    name="vacaciones_acumuladas"
                    class="emp-input @error('vacaciones_acumuladas') is-invalid @enderror" placeholder="0.00"
                    value="{{ old('vacaciones_acumuladas', $empleado?->vacaciones_acumuladas ?? 0) }}">
                @error('vacaciones_acumuladas')
                    <span class="emp-error">{{ $message }}</span>
                @enderror
                <p class="emp-hint">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                    Días no tomados de periodos anteriores al registro. Se descuentan primero y vencen el
                    {{ $venceHistorico->format('d-m-Y') }}.
                </p>
            </div>
        @endif

    </div>
</div>

<script>
    (function() {
        const identidad = document.getElementById('identidad');
        const telefono = document.getElementById('telefono');

        function formatearIdentidad() {
            const numeros = identidad.value.replace(/\D/g, '').slice(0, 13);
            identidad.value = [
                numeros.slice(0, 4),
                numeros.slice(4, 8),
                numeros.slice(8, 13)
            ].filter(Boolean).join('-');
        }

        identidad.addEventListener('input', formatearIdentidad);
        formatearIdentidad();

        function formatearTelefono() {
            let numeros = telefono.value.replace(/\D/g, '');

            if (numeros.startsWith('504')) {
                numeros = numeros.slice(3);
            }

            numeros = numeros.slice(0, 8);
            telefono.value = numeros ?
                `+504 ${numeros.slice(0, 4)}${numeros.length > 4 ? `-${numeros.slice(4)}` : ''}` :
                '';
        }

        telefono.addEventListener('focus', () => {
            if (!telefono.value) {
                telefono.value = '+504 ';
            }
        });
        telefono.addEventListener('blur', () => {
            if (telefono.value.trim() === '+504') {
                telefono.value = '';
            }
        });
        telefono.addEventListener('input', formatearTelefono);
        formatearTelefono();
    })();
</script>
