<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Nueva Empresa</h1>
            <a href="{{ route('cobranza.empresas.index') }}"
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

        <form method="POST" action="{{ route('cobranza.empresas.store') }}"
            class="bg-white rounded-2xl border p-6 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Nombre empresa</label>
                    <input name="nombre_empresa" value="{{ old('nombre_empresa') }}"
                        class="w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">RTN empresa</label>
                    <input name="rtn_empresa" value="{{ old('rtn_empresa') }}" class="w-full rounded-xl border-gray-300"
                        required>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Rubro / Actividad</label>
                    <input name="rubro_actividad" value="{{ old('rubro_actividad') }}"
                        class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Categoría</label>
                    <select name="categoria_id" class="w-full rounded-xl border-gray-300">
                        <option value="">—</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}" @selected(old('categoria_id') == $c->id)>{{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Tipo empresa</label>
                    <select name="tipo_empresa_id" class="w-full rounded-xl border-gray-300">
                        <option value="">—</option>
                        @foreach ($tipos as $t)
                            <option value="{{ $t->id }}" @selected(old('tipo_empresa_id') == $t->id)>{{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Capital declarado</label>
                    <input name="capital_declarado" type="number" step="0.01"
                        value="{{ old('capital_declarado') }}" class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Rango de capital (define
                        cuota/inscripción)</label>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Rango aplicado</label>
                        <input id="rango_info" readonly class="w-full rounded-xl border-gray-300 bg-gray-50"
                            placeholder="Se asigna automáticamente">
                    </div>

                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Cuota especial (opcional)</label>
                    <input name="cuota_especial" type="number" step="0.01" value="{{ old('cuota_especial') }}"
                        class="w-full rounded-xl border-gray-300">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Corte de facturación</label>
                    <select name="corte_id" class="w-full rounded-xl border-gray-300" required>
                        @foreach ($cortes as $c)
                            <option value="{{ $c->id }}" @selected(old('corte_id') == $c->id)>
                                {{ $c->nombre }} (día {{ $c->dia_corte }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Tipo de pago</label>
                    <select name="tipo_pago" class="w-full rounded-xl border-gray-300" required>
                        @foreach (['mensual', 'bimensual', 'trimestral', 'semestral', 'anual'] as $tp)
                            <option value="{{ $tp }}" @selected(old('tipo_pago', 'mensual') == $tp)>{{ strtoupper($tp) }}
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
                        <input name="direccion" value="{{ old('direccion') }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Ciudad</label>
                        <input name="ciudad" value="{{ old('ciudad') }}" class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Barrio/Colonia</label>
                        <input name="barrio_colonia" value="{{ old('barrio_colonia') }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            Coordenadas (Google Maps)
                        </label>
                        <input name="coordenadas" class="w-full rounded-xl border-gray-300"
                            placeholder="Ej: 13.29094649, -87.15233707">
                    </div>

                    <input type="hidden" name="latitud">
                    <input type="hidden" name="longitud">

                </div>
            </div>

            <div class="border-t pt-6">
                <h2 class="font-bold text-gray-900 mb-4">Responsables</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gerente Administrativo</label>
                        <input name="gerente_adm" value="{{ old('gerente_adm') }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gerente RRHH</label>
                        <input name="gerente_rrhh" value="{{ old('gerente_rrhh') }}"
                            class="w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gerente Contabilidad</label>
                        <input name="gerente_contabilidad" value="{{ old('gerente_contabilidad') }}"
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
                            <input name="telefonos_fijos[]" class="w-full rounded-xl border-gray-300"
                                placeholder="Ej: 2782-0000">
                        </div>
                    </div>

                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Celulares</h3>
                            <button type="button" class="text-blue-600 font-semibold"
                                onclick="addItem('celulares[]')">+ Agregar</button>
                        </div>
                        <div id="celulares_wrap" class="mt-3 space-y-2">
                            <input name="celulares[]" class="w-full rounded-xl border-gray-300"
                                placeholder="Ej: 9999-9999">
                        </div>
                    </div>

                    <div class="border rounded-2xl p-4 md:col-span-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Correos</h3>
                            <button type="button" class="text-blue-600 font-semibold"
                                onclick="addItem('correos[]')">+ Agregar</button>
                        </div>
                        <div id="correos_wrap" class="mt-3 space-y-2">
                            <input name="correos[]" type="email" class="w-full rounded-xl border-gray-300"
                                placeholder="correo@empresa.com">
                        </div>
                    </div>

                    <div class="border rounded-2xl p-4 md:col-span-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Propietarios</h3>
                            <button type="button" class="text-blue-600 font-semibold" onclick="addPropietario()">+
                                Agregar</button>
                        </div>

                        <div id="propietarios_wrap" class="mt-3 space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                <input name="propietarios[0][nombre]" class="rounded-xl border-gray-300"
                                    placeholder="Nombre">
                                <input name="propietarios[0][identidad]" class="rounded-xl border-gray-300"
                                    placeholder="Identidad">
                                <input name="propietarios[0][rtn]" class="rounded-xl border-gray-300"
                                    placeholder="RTN (opcional)">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="border-t pt-6">
                <label class="text-sm font-semibold text-gray-700">Comentario</label>
                <textarea name="comentario" class="w-full rounded-xl border-gray-300" rows="3">{{ old('comentario') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('cobranza.empresas.index') }}"
                    class="px-4 py-2 rounded-xl border hover:bg-gray-50">Cancelar</a>
                <button class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Guardar</button>
            </div>
        </form>
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

    <script>
        const rangos = @json($rangos);

        const tipoEmpresaSelect = document.querySelector('[name="tipo_empresa_id"]');
        const capitalInput = document.querySelector('[name="capital_declarado"]');
        const rangoInfo = document.getElementById('rango_info');

        function calcularRango() {
            const capital = parseFloat(capitalInput.value);
            const tipoEmpresaId = tipoEmpresaSelect.value;

            if (!tipoEmpresaId || isNaN(capital)) {
                rangoInfo.value = '';
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
            } else {
                rangoInfo.value = '⚠ No existe rango definido para este tipo';
            }
        }

        capitalInput.addEventListener('input', calcularRango);
        tipoEmpresaSelect.addEventListener('change', calcularRango);
    </script>


    <script>
        function addItem(name) {
            let wrapId = name.includes('telefonos_fijos') ? 'telefonos_fijos_wrap' :
                name.includes('celulares') ? 'celulares_wrap' :
                'correos_wrap';

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

        let propIndex = 1;

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
