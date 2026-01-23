<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Cobranza de Socios</h1>
            <a href="{{ route('cobranza.empresas.index') }}"
                class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                Ver Empresas
            </a>
        </div>

        {{-- MENSAJES --}}
        @if (session('success'))
            <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Empresas en mora</p>
                <p class="text-3xl font-bold text-gray-900">{{ $enMora }}</p>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Incobrables (≥ 18 cuotas)</p>
                <p class="text-3xl font-bold text-gray-900">{{ $incobrables }}</p>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Por vencer (7 días)</p>
                <p class="text-3xl font-bold text-gray-900">{{ $porVencer7 }}</p>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm text-gray-500">Monto pendiente real</p>
                <p class="text-3xl font-bold text-gray-900">
                    L. {{ number_format($montoPendiente, 2) }}
                </p>
            </div>
        </div>

        {{-- TOP MORA --}}
        <div class="bg-white rounded-2xl border overflow-hidden">
            <div class="p-5 border-b flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">
                    Top 10 empresas con mayor mora
                </h2>
                <a class="text-blue-600 hover:underline"
                    href="{{ route('cobranza.empresas.index', ['estatus' => 'en_mora']) }}">
                    Ver todas
                </a>
            </div>

            <div class="divide-y">
                @forelse ($topMora as $e)
                    <a href="{{ route('cobranza.empresas.show', $e) }}" class="block p-5 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $e->nombre_empresa }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    RTN: {{ $e->rtn_empresa }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-gray-900">
                                    L. {{ number_format($e->mora_real, 2) }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $e->pendientes_count }} cuotas pendientes
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-5 text-gray-400">
                        No hay empresas con mora.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
