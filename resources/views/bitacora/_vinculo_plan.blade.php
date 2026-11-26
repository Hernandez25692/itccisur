{{--
    Vínculo con el Plan de Trabajo (crear y editar bitácora).
    Recibe: $actividadesPlan (agrupadas "Plan AAAA · Sección"), $seleccionada (id o null)
    y $ejecucion (avance ya registrado por esta bitácora, o null).
--}}
@php
    $seleccionada = old('plan_actividad_id', $seleccionada ?? null);
    $ejecucion = $ejecucion ?? null;
@endphp

<div class="rounded-xl border border-[#C5A049]/40 bg-[#C5A049]/5 p-5 mb-6">
    <div class="flex items-start gap-3 mb-4">
        <span class="text-2xl" aria-hidden="true">🗂️</span>
        <div>
            <h3 class="font-semibold text-[#0C1C3C]">Plan de Trabajo TI <span class="font-normal text-gray-500">(opcional)</span></h3>
            <p class="text-sm text-gray-600">
                Si este trabajo corresponde a una actividad del plan aprobado, vincúlalo. Puedes sumar su avance
                y esta bitácora queda como evidencia en el plan.
            </p>
        </div>
    </div>

    @if ($ejecucion)
        <input type="hidden" name="plan_actividad_id" value="{{ $ejecucion->plan_actividad_id }}">
        <div class="rounded-lg bg-white border border-gray-200 p-4 text-sm text-gray-700">
            Esta bitácora ya sumó <strong>{{ $ejecucion->avance }}%</strong> a
            <a href="{{ route('plan-actividad.show', $ejecucion->plan_actividad_id) }}" class="font-semibold text-[#B59038] hover:underline">
                {{ $ejecucion->actividad?->actividad }}
            </a>
            (Plan {{ $ejecucion->actividad?->plan?->anio }}). El vínculo queda fijo para no descuadrar el avance.
        </div>
    @elseif ($actividadesPlan->isEmpty())
        <p class="text-sm text-gray-500">No hay un plan aprobado con actividades pendientes para vincular.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="planActividad" class="block text-sm font-medium text-gray-700 mb-1">Actividad del plan</label>
                <select name="plan_actividad_id" id="planActividad"
                    class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049]">
                    <option value="">— No corresponde al plan —</option>
                    @foreach ($actividadesPlan as $grupo => $items)
                        <optgroup label="{{ $grupo }}">
                            @foreach ($items as $a)
                                <option value="{{ $a->id }}" data-restante="{{ $a->restante }}" @selected((int) $seleccionada === $a->id)>
                                    {{ \Illuminate\Support\Str::limit($a->actividad, 90) }} ({{ $a->progreso }}%){{ $a->atrasada ? ' · atrasada' : '' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div id="avancePlanCampo">
                <label for="avancePlan" class="block text-sm font-medium text-gray-700 mb-1">Avance a sumar (%)</label>
                <input type="number" name="avance_plan" id="avancePlan" min="1" max="100" value="{{ old('avance_plan') }}"
                    class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#C5A049]/30 focus:border-[#C5A049]"
                    placeholder="Opcional">
                <p id="avancePlanAyuda" class="mt-1 text-xs text-gray-500">Vacío = solo vincular.</p>
            </div>
        </div>

        <script>
            (function () {
                const select = document.getElementById('planActividad');
                const campo = document.getElementById('avancePlanCampo');
                const input = document.getElementById('avancePlan');
                const ayuda = document.getElementById('avancePlanAyuda');

                function actualizar() {
                    const opcion = select.options[select.selectedIndex];
                    const restante = parseInt(opcion?.dataset.restante || '0', 10);

                    campo.style.opacity = select.value ? '1' : '.5';
                    input.disabled = !select.value || restante < 1;
                    input.max = Math.max(restante, 1);
                    if (!select.value) input.value = '';
                    ayuda.textContent = select.value
                        ? `Disponible: ${restante}%. Vacío = solo vincular.`
                        : 'Primero elige la actividad del plan.';
                }

                select.addEventListener('change', actualizar);
                actualizar();
            })();
        </script>
    @endif
</div>
