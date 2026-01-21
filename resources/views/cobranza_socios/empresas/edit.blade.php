<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Editar Empresa</h1>
            <a href="{{ route('cobranza.empresas.show', $empresa) }}"
                class="px-4 py-2 rounded-xl border hover:bg-gray-50">Volver</a>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="empresaForm" method="POST" action="{{ route('cobranza.empresas.update', $empresa) }}"
            class="bg-white rounded-2xl border p-6 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Nombre empresa</label>
                    <input name="nombre_empresa" value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}"
                        class="w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">RTN empresa</label>
                    <input name="rtn_empresa" value="{{ old('rtn_empresa', $empresa->rtn_empresa) }}"
                        class="w-full rounded-xl border-gray-300" required>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Rubro / Actividad</label>
                    <input name="rubro_actividad" value="{{ old('rubro_actividad', $empresa->rubro_actividad) }}"
                        class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Categoría</label>
                    <select name="categoria_id" class="w-full rounded-xl border-gray-300">
                        <option value="">—</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}" @selected(old('categoria_id', $empresa->categoria_id) == $c->id)>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Tipo empresa</label>
                    <select name="tipo_empresa_id" class="w-full rounded-xl border-gray-300">
                        <option value="">—</option>
                        @foreach ($tipos as $t)
                            <option value="{{ $t->id }}" @selected(old('tipo_empresa_id', $empresa->tipo_empresa_id) == $t->id)>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Capital declarado</label>
                    <input name="capital_declarado" type="number" step="0.01"
                        value="{{ old('capital_declarado', $empresa->capital_declarado) }}"
                        class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">
                        Rango de capital (según tipo y capital)
                    </label>

                    <input id="rango_info" readonly class="w-full rounded-xl border-gray-300 bg-gray-50"
                        placeholder="Se asigna automáticamente">
                </div>

                <input type="hidden" name="capital_rango_id" id="capital_rango_id">


                <div>
                    <label class="text-sm font-semibold text-gray-700">Cuota especial (opcional)</label>
                    <input name="cuota_especial" type="number" step="0.01"
                        value="{{ old('cuota_especial', $empresa->cuota_especial) }}"
                        class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Corte de facturación</label>
                    <select name="corte_id" class="w-full rounded-xl border-gray-300" required>
                        @foreach ($cortes as $c)
                            <option value="{{ $c->id }}" @selected(old('corte_id', $empresa->corte_id) == $c->id)>
                                {{ $c->nombre }} (día {{ $c->dia_corte }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Tipo de pago</label>
                    <select name="tipo_pago" class="w-full rounded-xl border-gray-300" required>
                        @foreach (['mensual', 'bimensual', 'trimestral', 'semestral', 'anual'] as $tp)
                            <option value="{{ $tp }}" @selected(old('tipo_pago', $empresa->tipo_pago) == $tp)>
                                {{ strtoupper($tp) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">
                        Fecha último pago (antes del sistema)
                    </label>
                    <input type="date" name="fecha_ultimo_pago_historico"
                        value="{{ old('fecha_ultimo_pago_historico', $empresa->fecha_ultimo_pago_historico ?? null) }}"
                        class="w-full rounded-xl border-gray-300">
                    <p class="text-xs text-gray-500 mt-1">
                        Solo usar para empresas que ya pagaban antes del sistema.
                    </p>
                </div>
            </div>

            <div class="border-t pt-6">
                <h2 class="font-bold text-gray-900 mb-4">Ubicación</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-700">Dirección</label>
                        <input name="direccion" value="{{ old('direccion', $empresa->direccion) }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Ciudad</label>
                        <input name="ciudad" value="{{ old('ciudad', $empresa->ciudad) }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Barrio/Colonia</label>
                        <input name="barrio_colonia" value="{{ old('barrio_colonia', $empresa->barrio_colonia) }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            Coordenadas (Google Maps)
                        </label>
                        <input name="coordenadas"
                            value="{{ old('coordenadas', $empresa->latitud && $empresa->longitud ? $empresa->latitud . ', ' . $empresa->longitud : '') }}"
                            class="w-full rounded-xl border-gray-300" placeholder="Ej: 13.29094649, -87.15233707">
                    </div>

                    <input type="hidden" name="latitud" value="{{ old('latitud', $empresa->latitud) }}">
                    <input type="hidden" name="longitud" value="{{ old('longitud', $empresa->longitud) }}">
                </div>
            </div>

            <script>
                const coordInput = document.querySelector('[name="coordenadas"]');
                const latInput = document.querySelector('[name="latitud"]');
                const lngInput = document.querySelector('[name="longitud"]');

                coordInput.addEventListener('change', () => {
                    const value = coordInput.value.trim();

                    // Formato: lat, lng
                    const match = value.match(/^(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)$/);

                    if (!match) {
                        alert('Formato inválido. Use: 13.2909, -87.1523');
                        latInput.value = '';
                        lngInput.value = '';
                        return;
                    }

                    const lat = parseFloat(match[1]);
                    const lng = parseFloat(match[3]);

                    if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                        alert('Coordenadas fuera de rango válido');
                        return;
                    }

                    latInput.value = lat;
                    lngInput.value = lng;
                });
            </script>

            <div class="border-t pt-6">
                <h2 class="font-bold text-gray-900 mb-4">Responsables</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gerente Administrativo</label>
                        <input name="gerente_adm" value="{{ old('gerente_adm', $empresa->gerente_adm) }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gerente RRHH</label>
                        <input name="gerente_rrhh" value="{{ old('gerente_rrhh', $empresa->gerente_rrhh) }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gerente Contabilidad</label>
                        <input name="gerente_contabilidad"
                            value="{{ old('gerente_contabilidad', $empresa->gerente_contabilidad) }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                </div>
            </div>

            <div class="border-t pt-6">
                <h2 class="font-bold text-gray-900 mb-4">Contactos múltiples</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Teléfonos fijos</h3>
                            <button type="button" class="text-blue-600 font-semibold"
                                onclick="addItem('telefonos_fijos[]')">+ Agregar</button>
                        </div>
                        <div id="telefonos_fijos_wrap" class="mt-3 space-y-2">
                            @forelse ($empresa->telefonosFijos as $t)
                                <div class="flex gap-2">
                                    <input name="telefonos_fijos[]" value="{{ $t->telefono }}"
                                        class="w-full rounded-xl border-gray-300" placeholder="Ej: 2782-0000">
                                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                        onclick="this.parentElement.remove()">X</button>
                                </div>
                            @empty
                                <div class="flex gap-2">
                                    <input name="telefonos_fijos[]" class="w-full rounded-xl border-gray-300"
                                        placeholder="Ej: 2782-0000">
                                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                        onclick="this.parentElement.remove()">X</button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Celulares</h3>
                            <button type="button" class="text-blue-600 font-semibold"
                                onclick="addItem('celulares[]')">+ Agregar</button>
                        </div>
                        <div id="celulares_wrap" class="mt-3 space-y-2">
                            @forelse ($empresa->celulares as $c)
                                <div class="flex gap-2">
                                    <input name="celulares[]" value="{{ $c->celular }}"
                                        class="w-full rounded-xl border-gray-300" placeholder="Ej: 9999-9999">
                                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                        onclick="this.parentElement.remove()">X</button>
                                </div>
                            @empty
                                <div class="flex gap-2">
                                    <input name="celulares[]" class="w-full rounded-xl border-gray-300"
                                        placeholder="Ej: 9999-9999">
                                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                        onclick="this.parentElement.remove()">X</button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="border rounded-2xl p-4 md:col-span-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Correos</h3>
                            <button type="button" class="text-blue-600 font-semibold"
                                onclick="addItem('correos[]')">+ Agregar</button>
                        </div>
                        <div id="correos_wrap" class="mt-3 space-y-2">
                            @forelse ($empresa->correos as $e)
                                <div class="flex gap-2">
                                    <input name="correos[]" type="email" value="{{ $e->correo }}"
                                        class="w-full rounded-xl border-gray-300" placeholder="correo@empresa.com">
                                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                        onclick="this.parentElement.remove()">X</button>
                                </div>
                            @empty
                                <div class="flex gap-2">
                                    <input name="correos[]" type="email" class="w-full rounded-xl border-gray-300"
                                        placeholder="correo@empresa.com">
                                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                        onclick="this.parentElement.remove()">X</button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="border rounded-2xl p-4 md:col-span-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Propietarios</h3>
                            <button type="button" class="text-blue-600 font-semibold" onclick="addPropietario()">+
                                Agregar</button>
                        </div>

                        <div id="propietarios_wrap" class="mt-3 space-y-3">
                            @forelse ($empresa->propietarios as $idx => $p)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    <input name="propietarios[{{ $idx }}][nombre]"
                                        value="{{ old("propietarios.$idx.nombre", $p->nombre) }}"
                                        class="rounded-xl border-gray-300" placeholder="Nombre">
                                    <input name="propietarios[{{ $idx }}][identidad]"
                                        value="{{ old("propietarios.$idx.identidad", $p->identidad) }}"
                                        class="rounded-xl border-gray-300" placeholder="Identidad">
                                    <div class="flex gap-2">
                                        <input name="propietarios[{{ $idx }}][rtn]"
                                            value="{{ old("propietarios.$idx.rtn", $p->rtn) }}"
                                            class="rounded-xl border-gray-300 w-full" placeholder="RTN (opcional)">
                                        <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                            onclick="this.parentElement.parentElement.remove()">X</button>
                                    </div>
                                </div>
                            @empty
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    <input name="propietarios[0][nombre]" class="rounded-xl border-gray-300"
                                        placeholder="Nombre">
                                    <input name="propietarios[0][identidad]" class="rounded-xl border-gray-300"
                                        placeholder="Identidad">
                                    <div class="flex gap-2">
                                        <input name="propietarios[0][rtn]" class="rounded-xl border-gray-300 w-full"
                                            placeholder="RTN (opcional)">
                                        <button type="button" class="px-3 rounded-xl border hover:bg-gray-50"
                                            onclick="this.parentElement.parentElement.remove()">X</button>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            <div class="border-t pt-6">
                <label class="text-sm font-semibold text-gray-700">Comentario</label>
                <textarea name="comentario" class="w-full rounded-xl border-gray-300" rows="3">{{ old('comentario', $empresa->comentario) }}</textarea>
            </div>

            <div class="border-t pt-6 bg-red-50 rounded-xl p-4">
                <h2 class="font-bold text-red-900 mb-4">Zona de peligro</h2>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="marcar_inactiva"
                                onclick="document.getElementById('motivo_inactivacion').style.display = this.checked ? 'block' : 'none'"
                                class="rounded">
                            <span class="font-semibold text-red-900">Marcar como inactiva</span>
                        </label>
                        <div id="motivo_inactivacion" style="display: none;" class="mt-2">
                            <input name="motivo_inactivacion" type="text"
                                placeholder="Motivo de inactivación (opcional)"
                                class="w-full rounded-xl border-gray-300">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('cobranza.empresas.show', $empresa) }}"
                    class="px-4 py-2 rounded-xl border hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Guardar
                    cambios</button>
            </div>
        </form>
    </div>

    <!-- Modal de Validación -->
    <div id="validationModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Error de validación</h3>
            </div>
            <ul id="validationErrors" class="space-y-2 mb-6 text-red-700 text-sm list-disc pl-5"></ul>
            <button onclick="closeValidationModal()"
                class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                Entendido
            </button>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Confirmar cambios</h3>
            </div>
            <p id="confirmMessage" class="text-gray-700 mb-6">¿Está seguro de que desea guardar los cambios en esta
                empresa?</p>
            <div class="flex gap-3">
                <button onclick="closeConfirmModal()" class="flex-1 px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Cancelar
                </button>
                <button onclick="submitForm()"
                    class="flex-1 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Salida sin guardar -->
    <div id="leaveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4v2m0 0v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Cambios sin guardar</h3>
            </div>
            <p class="text-gray-700 mb-6">Tienes cambios sin guardar. ¿Deseas abandonar sin guardar?</p>
            <div class="flex gap-3">
                <button onclick="closeLeaveModal()" class="flex-1 px-4 py-2 rounded-xl border hover:bg-gray-50">
                    Seguir editando
                </button>
                <button onclick="confirmLeave()"
                    class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">
                    Abandonar
                </button>
            </div>
        </div>
    </div>
    <script>
        const rangos = @json($rangos);

        const tipoEmpresaSelect = document.querySelector('[name="tipo_empresa_id"]');
        const capitalInput = document.querySelector('[name="capital_declarado"]');
        const rangoInfo = document.getElementById('rango_info');
        const rangoHidden = document.getElementById('capital_rango_id');

        function calcularRango() {
            const capital = parseFloat(capitalInput.value);
            const tipoEmpresaId = tipoEmpresaSelect.value;

            if (!tipoEmpresaId || isNaN(capital)) {
                rangoInfo.value = '';
                rangoHidden.value = '';
                return;
            }

            const rango = rangos.find(r =>
                r.tipo_empresa_id == tipoEmpresaId &&
                capital >= parseFloat(r.capital_min) &&
                capital <= parseFloat(r.capital_max)
            );

            if (rango) {
                rangoInfo.value =
                    `${rango.capital_min} - ${rango.capital_max} | Cuota L. ${rango.cuota_mensual}`;
                rangoHidden.value = rango.id;
            } else {
                rangoInfo.value = '⚠ No existe rango definido para este tipo';
                rangoHidden.value = '';
            }
        }

        // Inicializar al cargar
        document.addEventListener('DOMContentLoaded', calcularRango);

        capitalInput.addEventListener('input', calcularRango);
        tipoEmpresaSelect.addEventListener('change', calcularRango);
    </script>

    <script>
        const form = document.getElementById('empresaForm');
        const initialFormData = new FormData(form);
        let pendingLeave = null;

        // Validaciones
        const validationRules = {
            nombre_empresa: {
                required: true,
                message: 'El nombre de la empresa es obligatorio'
            },
            corte_id: {
                required: true,
                message: 'El corte de facturación es obligatorio'
            },
            tipo_pago: {
                required: true,
                message: 'El tipo de pago es obligatorio'
            }
        };

        function validateForm() {
            const errors = [];

            Object.keys(validationRules).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (!field) return;

                const value = field.value.trim();
                const rule = validationRules[fieldName];

                if (rule.required && !value) {
                    errors.push(rule.message);
                } else if (rule.pattern && value && !rule.pattern.test(value)) {
                    errors.push(rule.message);
                }
            });

            // Validar correos
            const correos = form.querySelectorAll('input[name="correos[]"]');
            correos.forEach((correo, idx) => {
                if (correo.value && !correo.value.includes('@')) {
                    errors.push(`El correo ${idx + 1} no es válido`);
                }
            });

            // Validar propietarios con identidad
            const propietarios = document.getElementById('propietarios_wrap').querySelectorAll('.grid');
            propietarios.forEach((prop, idx) => {
                const inputs = prop.querySelectorAll('input');
                const nombre = inputs[0]?.value.trim();
                const identidad = inputs[1]?.value.trim();

                if (nombre && !identidad) {
                    errors.push(`Propietario ${idx + 1}: La identidad es obligatoria`);
                }
                if (identidad && !nombre) {
                    errors.push(`Propietario ${idx + 1}: El nombre es obligatorio`);
                }
            });

            return errors;
        }

        function showValidationModal(errors) {
            const errorsList = document.getElementById('validationErrors');
            errorsList.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
            document.getElementById('validationModal').classList.remove('hidden');
        }

        function closeValidationModal() {
            document.getElementById('validationModal').classList.add('hidden');
        }

        function showConfirmModal() {
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function submitForm() {
            closeConfirmModal();
            form.submit();
        }

        function hasChanges() {
            const currentData = new FormData(form);
            const entries1 = new Set(Object.entries(Object.fromEntries(initialFormData)));
            const entries2 = new Set(Object.entries(Object.fromEntries(currentData)));
            return entries1.size !== entries2.size || ![...entries1].some(e => entries2.has(JSON.stringify(e)));
        }

        function showLeaveModal(callback) {
            pendingLeave = callback;
            document.getElementById('leaveModal').classList.remove('hidden');
        }

        function closeLeaveModal() {
            document.getElementById('leaveModal').classList.add('hidden');
            pendingLeave = null;
        }

        function confirmLeave() {
            if (pendingLeave) pendingLeave();
        }

        // Event listeners
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const errors = validateForm();

            if (errors.length > 0) {
                showValidationModal(errors);
                return;
            }

            showConfirmModal();
        });

        // Prevenir salida sin guardar
        window.addEventListener('beforeunload', (e) => {
            if (hasChanges()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Interceptar links
        document.addEventListener('click', (e) => {
            if (e.target.tagName === 'A' && e.target.href && !e.target.href.includes('javascript')) {
                if (hasChanges()) {
                    e.preventDefault();
                    showLeaveModal(() => window.location.href = e.target.href);
                }
            }
        });

        function addItem(name) {
            let wrapId = name.includes('telefonos_fijos') ? 'telefonos_fijos_wrap' :
                name.includes('celulares') ? 'celulares_wrap' : 'correos_wrap';

            const wrap = document.getElementById(wrapId);
            const input = document.createElement('input');
            input.name = name;
            input.className = 'w-full rounded-xl border-gray-300';
            input.placeholder = (wrapId === 'correos_wrap') ? 'correo@empresa.com' : '';
            if (wrapId === 'correos_wrap') input.type = 'email';

            const row = document.createElement('div');
            row.className = 'flex gap-2';
            row.appendChild(input);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-3 rounded-xl border hover:bg-gray-50';
            btn.innerText = 'X';
            btn.onclick = () => row.remove();
            row.appendChild(btn);

            wrap.appendChild(row);
        }

        let propIndex = {{ $empresa->propietarios->count() }};

        function addPropietario() {
            const wrap = document.getElementById('propietarios_wrap');
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-3 gap-2';

            div.innerHTML = `
                <input name="propietarios[${propIndex}][nombre]" class="rounded-xl border-gray-300" placeholder="Nombre">
                <input name="propietarios[${propIndex}][identidad]" class="rounded-xl border-gray-300" placeholder="Identidad">
                <div class="flex gap-2">
                    <input name="propietarios[${propIndex}][rtn]" class="rounded-xl border-gray-300 w-full" placeholder="RTN (opcional)">
                    <button type="button" class="px-3 rounded-xl border hover:bg-gray-50" onclick="this.parentElement.parentElement.remove()">X</button>
                </div>
            `;
            wrap.appendChild(div);
            propIndex++;
        }
    </script>
</x-app-layout>
