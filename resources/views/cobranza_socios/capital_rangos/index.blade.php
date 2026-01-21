<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                Rangos de Capital (Cuota / Inscripción)
            </h1>
        </div>

        @if (session('success'))
            <div class="p-3 rounded-xl bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORMULARIO NUEVO RANGO --}}
        <form method="POST" action="{{ route('cobranza.capital-rangos.store') }}"
            class="bg-white rounded-2xl border p-6 space-y-4">
            @csrf

            <h2 class="font-semibold text-gray-900">Nuevo Rango</h2>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">

                {{-- Tipo de empresa --}}
                <select name="tipo_empresa_id" required class="rounded-xl border-gray-300">
                    <option value="">Tipo de empresa</option>
                    @foreach ($tiposEmpresa as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>

                <input name="capital_min" type="number" step="0.01" required class="rounded-xl border-gray-300"
                    placeholder="Capital mínimo">

                <input name="capital_max" type="number" step="0.01" required class="rounded-xl border-gray-300"
                    placeholder="Capital máximo">

                <input name="cuota_mensual" type="number" step="0.01" required class="rounded-xl border-gray-300"
                    placeholder="Cuota mensual">

                <input name="inscripcion" type="number" step="0.01" class="rounded-xl border-gray-300"
                    placeholder="Inscripción">
            </div>

            <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                Guardar rango
            </button>
        </form>

        {{-- TABLA DE RANGOS --}}
        <div class="bg-white rounded-2xl border overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Tipo Empresa</th>
                        <th class="p-3 text-left">Capital</th>
                        <th class="p-3">Cuota</th>
                        <th class="p-3">Inscripción</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($rangos as $r)
                        <tr>
                            <td class="p-3 font-semibold text-gray-800">
                                {{ $r->tipoEmpresa->nombre }}
                            </td>
                            <td class="p-3 font-semibold">
                                {{ number_format($r->capital_min, 2) }} –
                                {{ number_format($r->capital_max, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                L. {{ number_format($r->cuota_mensual, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                L. {{ number_format($r->inscripcion, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                <span class="{{ $r->activo ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $r->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <form method="POST" action="{{ route('cobranza.capital-rangos.update', $r) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="activo" value="{{ !$r->activo }}">
                                    <button class="px-3 py-1 rounded-xl border hover:bg-gray-50">
                                        {{ $r->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                No hay rangos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
