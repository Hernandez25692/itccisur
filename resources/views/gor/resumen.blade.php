<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                        📊 Resumen GOR
                    </h1>
                    <p class="text-sm text-gray-600">
                        Revisión de Antecedentes Registrales + Control de Audiencias 
                    </p>
                </div>

                <a href="{{ route('gor.antecedentes.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-sm font-semibold">
                    Ver Antecedentes
                </a>
            </div>

            {{-- Filtros --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Circunscripción</label>
                        <select name="circunscripcion"
                            class="w-full mt-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todas</option>
                            @foreach ($circunscripciones as $c)
                                <option value="{{ $c }}"
                                    {{ request('circunscripcion') == $c ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Fecha desde</label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="w-full mt-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Fecha hasta</label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="w-full mt-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex gap-2">
                        <button
                            class="flex-1 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                            Aplicar
                        </button>
                        <a href="{{ route('gor.resumen') }}"
                            class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- KPIs --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-semibold text-gray-500">Total Antecedentes</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalAntecedentes) }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-semibold text-gray-500">Total Audiencias</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalAudiencias) }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-semibold text-gray-500">Audiencias Atendidas</p>
                    <p class="text-3xl font-extrabold text-green-600 mt-1">{{ number_format($audAtendidas) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pendientes: {{ number_format($audPendientes) }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-semibold text-gray-500">Tiempo respuesta (Audiencias)</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        Prom: {{ $promedioHoras }}h · Med: {{ $medianaHoras }}h · P90: {{ $p90Horas }}h
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Pendientes (prom): {{ $pendientesDiasProm }} días</p>
                </div>
            </div>

            {{-- Gráficas --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-extrabold text-gray-900 mb-3">📈 Tendencia por mes (últimos 12)</h2>
                    <canvas id="trendChart" height="120"></canvas>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-extrabold text-gray-900 mb-3">🗺️ Antecedentes por Circunscripción</h2>
                    <canvas id="circChart" height="120"></canvas>
                </div>
            </div>

            {{-- Rankings --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-extrabold text-gray-900 mb-3">🏷️ Asiento/Tomo/Matrícula más consultado</h2>
                    <ul class="space-y-2">
                        @forelse($topAsientos as $row)
                            <li class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-800 truncate">{{ $row->asiento_tomo_matricula }}</span>
                                <span class="text-xs font-bold bg-blue-50 text-blue-700 px-2 py-1 rounded-full">
                                    {{ $row->total }}
                                </span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">Sin datos todavía.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-extrabold text-gray-900 mb-3">📚 Tipo de libro más consultado</h2>
                    <ul class="space-y-2">
                        @forelse($topLibros as $row)
                            <li class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-800 truncate">{{ $row->tipo_libro }}</span>
                                <span class="text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">
                                    {{ $row->total }}
                                </span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">Sin datos todavía.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-extrabold text-gray-900 mb-3">👤 Quién solicita más</h2>

                    <p class="text-xs font-semibold text-gray-500 mb-2">Antecedentes</p>
                    <ul class="space-y-2 mb-4">
                        @forelse($topSolicitantesAnt as $row)
                            <li class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-800 truncate">{{ $row->solicitante_nombre }}</span>
                                <span class="text-xs font-bold bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full">
                                    {{ $row->total }}
                                </span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">Sin datos todavía.</li>
                        @endforelse
                    </ul>

                    <p class="text-xs font-semibold text-gray-500 mb-2">Audiencias</p>
                    <ul class="space-y-2">
                        @forelse($topSolicitantesAud as $row)
                            <li class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-800 truncate">{{ $row->nombre_solicitante }}</span>
                                <span class="text-xs font-bold bg-amber-50 text-amber-700 px-2 py-1 rounded-full">
                                    {{ $row->total }}
                                </span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">Sin datos todavía.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ========= Tendencia por mes =========
        const allMonths = (() => {
            const start = new Date();
            start.setDate(1);
            start.setMonth(start.getMonth() - 11);
            const months = [];
            for (let i = 0; i < 12; i++) {
                const y = start.getFullYear();
                const m = String(start.getMonth() + 1).padStart(2, '0');
                months.push(`${y}-${m}`);
                start.setMonth(start.getMonth() + 1);
            }
            return months;
        })();

        const antDataRaw = @json($antPorMes);
        const audDataRaw = @json($audPorMes);

        const antMap = Object.fromEntries(antDataRaw.map(x => [x.mes, Number(x.total)]));
        const audMap = Object.fromEntries(audDataRaw.map(x => [x.mes, Number(x.total)]));

        const antSeries = allMonths.map(m => antMap[m] ?? 0);
        const audSeries = allMonths.map(m => audMap[m] ?? 0);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: allMonths,
                datasets: [{
                        label: 'Antecedentes',
                        data: antSeries,
                        tension: 0.25
                    },
                    {
                        label: 'Audiencias',
                        data: audSeries,
                        tension: 0.25
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // ========= Circunscripción =========
        const circRaw = @json($porCircunscripcion);
        const circLabels = circRaw.map(x => x.circunscripcion ?? 'Sin definir');
        const circTotals = circRaw.map(x => Number(x.total));

        new Chart(document.getElementById('circChart'), {
            type: 'bar',
            data: {
                labels: circLabels,
                datasets: [{
                    label: 'Total',
                    data: circTotals
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
