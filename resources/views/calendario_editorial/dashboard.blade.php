<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-10">

        <div
            class="flex flex-col lg:flex-row lg:justify-between gap-6 items-center bg-gradient-to-r from-gray-900 to-blue-900 p-6 rounded-2xl shadow-2xl">
            <div>
                <h1 class="text-4xl font-extrabold text-white mb-2 tracking-tight">
                    📊 Dashboard Editorial Pro
                </h1>
                <p class="text-blue-300 font-medium text-lg">
                    Análisis de Publicaciones: {{ \Carbon\Carbon::createFromDate($anio, $mes, 1)->translatedFormat('F Y') }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-500 p-2 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">📅</span>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Periodo seleccionado</p>
                            <p class="text-lg font-bold text-white">
                                @if(request('filtro') === 'todo')
                                    Todo el historial
                                @else
                                    {{ \Carbon\Carbon::createFromDate($anio, $mes, 1)->translatedFormat('F Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <form method="GET"
                    class="flex gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/20 items-end flex-wrap">
                    
                    <div class="flex gap-2 items-end">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="filtro" value="todo" @checked(request('filtro') === 'todo')
                                class="w-4 h-4 text-cyan-500 rounded focus:ring-2 focus:ring-cyan-400">
                            <span class="text-sm font-medium text-blue-100">Todo</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="filtro" value="especifico" @checked(request('filtro') !== 'todo' || !request('filtro'))
                                class="w-4 h-4 text-cyan-500 rounded focus:ring-2 focus:ring-cyan-400">
                            <span class="text-sm font-medium text-blue-100">Mes/Año</span>
                        </label>
                    </div>

                    <div class="flex flex-col" id="mesSelect" @style("display: " . (request('filtro') === 'todo' ? 'none' : 'block'))>
                        <label class="text-xs text-blue-200 mb-1 font-semibold">Mes</label>
                        <select name="mes"
                            class="bg-white/90 border-0 rounded-lg p-2 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-cyan-400 focus:outline-none shadow-md">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" @selected($mes == $m)>
                                    {{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col" id="anioSelect" @style("display: " . (request('filtro') === 'todo' ? 'none' : 'block'))>
                        <label class="text-xs text-blue-200 mb-1 font-semibold">Año</label>
                        <select name="anio"
                            class="bg-white/90 border-0 rounded-lg p-2 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-cyan-400 focus:outline-none shadow-md">
                            @foreach (range(now()->year - 2, now()->year + 1) as $y)
                                <option value="{{ $y }}" @selected($anio == $y)>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="px-5 py-2.5 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-lg font-semibold hover:shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] shadow-blue-500/50">
                        🔍 Aplicar
                    </button>
                </form>
            </div>

            <script>
                document.querySelectorAll('input[name="filtro"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const mesSelect = document.getElementById('mesSelect');
                        const anioSelect = document.getElementById('anioSelect');
                        if (this.value === 'todo') {
                            mesSelect.style.display = 'none';
                            anioSelect.style.display = 'none';
                        } else {
                            mesSelect.style.display = 'block';
                            anioSelect.style.display = 'block';
                        }
                    });
                });
            </script>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div
                class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 border-b-4 border-blue-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-light text-blue-200 uppercase tracking-widest">Total Publicaciones</p>
                        <p class="text-6xl font-extrabold mt-2">{{ $totalMes }}</p>
                        <p class="text-sm mt-1 text-blue-200">En el periodo</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <span class="text-3xl">📊</span>
                    </div>
                </div>
                <div class="mt-4 w-full h-2 bg-blue-500/30 rounded-full">
                    <div class="bg-white h-full rounded-full w-full"></div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 border-b-4 border-emerald-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-light text-emerald-200 uppercase tracking-widest">Publicadas</p>
                        <p class="text-6xl font-extrabold mt-2">{{ $porEstado['publicado'] ?? 0 }}</p>
                        <p class="text-sm mt-1 text-emerald-200">Éxito total</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <span class="text-3xl">✅</span>
                    </div>
                </div>
                <div class="mt-4 w-full h-2 bg-emerald-500/30 rounded-full">
                    <div class="bg-white h-full rounded-full"
                        style="width: {{ $totalMes > 0 ? (($porEstado['publicado'] ?? 0) / $totalMes) * 100 : 0 }}%">
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-amber-600 to-amber-800 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 border-b-4 border-amber-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-light text-amber-200 uppercase tracking-widest">Pendientes</p>
                        <p class="text-6xl font-extrabold mt-2">{{ $porEstado['pendiente'] ?? 0 }}</p>
                        <p class="text-sm mt-1 text-amber-200">En proceso</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <span class="text-3xl">⏳</span>
                    </div>
                </div>
                <div class="mt-4 w-full h-2 bg-amber-500/30 rounded-full">
                    <div class="bg-white h-full rounded-full"
                        style="width: {{ $totalMes > 0 ? (($porEstado['pendiente'] ?? 0) / $totalMes) * 100 : 0 }}%">
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-violet-600 to-violet-800 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 border-b-4 border-violet-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-light text-violet-200 uppercase tracking-widest">Cumplimiento</p>
                        <p class="text-6xl font-extrabold mt-2">{{ $cumplimiento }}%</p>
                        <p class="text-sm mt-1 text-violet-200">Meta lograda</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <span class="text-3xl">🎯</span>
                    </div>
                </div>
                <div class="mt-4 w-full h-2 bg-violet-500/30 rounded-full">
                    <div class="bg-white h-full rounded-full" style="width: {{ $cumplimiento }}%"></div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <div class="bg-blue-600 p-2 rounded-lg">
                                <span class="text-white">📈</span>
                            </div>
                            Publicaciones por Semana
                        </h3>
                        <div class="text-sm font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full border border-blue-200">
                            Total: {{ $porSemana->sum('total') }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="chartSemana"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <div class="bg-emerald-600 p-2 rounded-lg">
                                <span class="text-white">📅</span>
                            </div>
                            Publicaciones por Día
                        </h3>
                        <div class="text-sm font-bold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200">
                            Promedio: {{ number_format($porDia->avg('total') ?? 0, 1) }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="chartDia"></canvas>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <div class="bg-violet-600 p-2 rounded-lg">
                                <span class="text-white">🌐</span>
                            </div>
                            Distribución por Redes Sociales
                        </h3>
                        <div class="text-sm font-bold text-violet-700 bg-violet-100 px-3 py-1 rounded-full border border-violet-200">
                            Total redes: {{ $porRed->count() }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                        <div class="lg:col-span-2">
                            <div class="relative h-96 lg:h-80">
                                <canvas id="chartRed"></canvas>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h4 class="font-extrabold text-gray-800 text-lg border-b pb-2">Desglose por Red</h4>
                            @foreach ($porRed as $red => $cantidad)
                                @php
                                    $icon_map = ['FACEBOOK' => '📘', 'INSTAGRAM' => '📸', 'LINKEDIN' => '💼', 'WHATSAPP' => '💬', 'TIKTOK' => '🎵', 'TWITTER' => '🐦', 'PINTEREST' => '📌'];
                                    $gradient_map = ['FACEBOOK' => 'from-blue-500 to-indigo-600', 'INSTAGRAM' => 'from-pink-500 to-rose-600', 'LINKEDIN' => 'from-blue-700 to-cyan-700', 'WHATSAPP' => 'from-green-500 to-emerald-600', 'TIKTOK' => 'from-gray-800 to-black', 'TWITTER' => 'from-cyan-400 to-blue-400', 'PINTEREST' => 'from-red-600 to-red-700'];
                                    $icon = $icon_map[$red] ?? '🔗';
                                    $gradient = $gradient_map[$red] ?? 'from-gray-500 to-gray-600';
                                @endphp
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-violet-50 transition border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r {{ $gradient }} flex items-center justify-center shadow-md flex-shrink-0">
                                            <span class="text-white text-sm">{{ $icon }}</span>
                                        </div>
                                        <span class="font-semibold text-gray-700">{{ $red }}</span>
                                    </div>
                                    <span class="text-2xl font-extrabold text-violet-800">{{ $cantidad }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <div class="bg-amber-600 p-2 rounded-lg">
                        <span class="text-white">📆</span>
                    </div>
                    Distribución Semanal
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg">
                            <th class="px-6 py-4 text-left font-extrabold text-sm uppercase tracking-wider rounded-tl-2xl">
                                Semana</th>
                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <th class="px-6 py-4 text-center font-extrabold text-sm uppercase tracking-wider">
                                    {{ substr($dia, 0, 3) }}</th>
                            @endforeach
                            <th
                                class="px-6 py-4 text-center font-extrabold text-sm uppercase tracking-wider rounded-tr-2xl bg-amber-700/80">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablaPorDiaSemana as $index => $fila)
                            <tr
                                class="hover:bg-amber-50/50 transition border-b border-gray-100 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $fila['semana'] }}
                                </td>
                                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                    <td class="px-6 py-4 text-center">
                                        @if ($fila[$dia] > 0)
                                            <span
                                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold shadow-md text-base">
                                                {{ $fila[$dia] }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 font-semibold">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 text-center bg-amber-100/70">
                                    <span
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 text-white font-extrabold text-lg shadow-xl shadow-amber-500/30">
                                        {{ $fila['Lunes'] + ($fila['Martes'] ?? 0) + ($fila['Miércoles'] ?? 0) + ($fila['Jueves'] ?? 0) + ($fila['Viernes'] ?? 0) + ($fila['Sábado'] ?? 0) + ($fila['Domingo'] ?? 0) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <div class="bg-indigo-600 p-2 rounded-lg">
                        <span class="text-white">🎬</span>
                    </div>
                    Contenido por Semana (Tipo)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg">
                            <th class="px-6 py-4 text-left font-extrabold text-sm uppercase tracking-wider rounded-tl-2xl">
                                Semana</th>
                            @foreach (['VIV' => '🎬 VIV', 'IMG' => '🖼️ IMG', 'CAR' => '🎠 CAR', 'HIST' => '📖 HIST', 'VID' => '🎥 VID'] as $key => $label)
                                <th class="px-6 py-4 text-center font-extrabold text-sm uppercase tracking-wider">
                                    {{ $label }}</th>
                            @endforeach
                            <th
                                class="px-6 py-4 text-center font-extrabold text-sm uppercase tracking-wider rounded-tr-2xl bg-indigo-700/80">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablaContenidoPorSemana as $index => $fila)
                            <tr
                                class="hover:bg-indigo-50/50 transition border-b border-gray-100 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $fila['semana'] }}
                                </td>
                                @foreach (['VIV', 'IMG', 'CAR', 'HIST', 'VID'] as $tipo)
                                    <td class="px-6 py-4 text-center">
                                        @if ($fila[$tipo] > 0)
                                            @php
                                                $colors = [
                                                    'VIV' => 'from-red-500 to-pink-600',
                                                    'IMG' => 'from-purple-500 to-violet-600',
                                                    'CAR' => 'from-cyan-500 to-blue-600',
                                                    'HIST' => 'from-yellow-500 to-amber-600',
                                                    'VID' => 'from-pink-500 to-rose-600',
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r {{ $colors[$tipo] }} text-white font-bold shadow-md text-base">
                                                {{ $fila[$tipo] }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 font-semibold">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 text-center bg-indigo-100/70">
                                    <span
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-extrabold text-lg shadow-xl shadow-indigo-500/30">
                                        {{ $fila['VIV'] + ($fila['IMG'] ?? 0) + ($fila['CAR'] ?? 0) + ($fila['HIST'] ?? 0) + ($fila['VID'] ?? 0) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        // Registrar plugin de etiquetas
        Chart.register(ChartDataLabels);

        // Colores mejorados y diferenciados
        const colors = {
            blue: {
                bg: 'rgba(59, 130, 246, 0.1)',
                border: 'rgb(59, 130, 246)',
                gradient: ['rgba(59, 130, 246, 0.95)', 'rgba(29, 78, 216, 0.95)'],
                light: 'rgba(59, 130, 246, 0.7)'
            },
            green: {
                bg: 'rgba(16, 185, 129, 0.1)',
                border: 'rgb(16, 185, 129)',
                gradient: ['rgba(16, 185, 129, 0.95)', 'rgba(5, 150, 105, 0.95)'],
                light: 'rgba(16, 185, 129, 0.7)'
            },
            purple: {
                bg: 'rgba(168, 85, 247, 0.1)',
                border: 'rgb(168, 85, 247)',
                gradient: ['rgba(168, 85, 247, 0.95)', 'rgba(147, 51, 234, 0.95)'],
                light: 'rgba(168, 85, 247, 0.7)'
            }
        };

        // ========== GRÁFICA SEMANA ==========
        const dataSemana = @json($porSemana->pluck('total'));
        const ctxSemana = document.getElementById('chartSemana').getContext('2d');
        const gradientSemana = ctxSemana.createLinearGradient(0, 0, 0, 400);
        gradientSemana.addColorStop(0, colors.blue.gradient[0]);
        gradientSemana.addColorStop(1, colors.blue.gradient[1]);

        new Chart(ctxSemana, {
            type: 'bar',
            data: {
                labels: @json(
                    $porSemana->pluck('semana')->map(function ($s) {
                        return 'Semana ' . $s;
                    })),
                datasets: [{
                    label: 'Publicaciones',
                    data: dataSemana,
                    backgroundColor: gradientSemana,
                    borderColor: colors.blue.border,
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { weight: 'bold', size: 12 },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        color: '#1f2937',
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        borderRadius: 6,
                        padding: 6
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: colors.blue.border,
                        borderWidth: 2,
                        padding: 10,
                        titleFont: { weight: 'bold', size: 12 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // ========== GRÁFICA DÍA ==========
        const dataDia = @json($porDia->pluck('total'));
        const ctxDia = document.getElementById('chartDia').getContext('2d');
        const gradientDia = ctxDia.createLinearGradient(0, 0, 0, 400);
        gradientDia.addColorStop(0, colors.green.gradient[0]);
        gradientDia.addColorStop(1, colors.green.gradient[1]);

        new Chart(ctxDia, {
            type: 'bar',
            data: {
                labels: @json($porDia->pluck('dia')),
                datasets: [{
                    label: 'Publicaciones',
                    data: dataDia,
                    backgroundColor: gradientDia,
                    borderColor: colors.green.border,
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { weight: 'bold', size: 12 },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        color: '#1f2937',
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        borderRadius: 6,
                        padding: 6
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: colors.green.border,
                        borderWidth: 2,
                        padding: 10,
                        titleFont: { weight: 'bold', size: 12 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // ========== GRÁFICA RED (DOUGHNUT) ==========
        const dataRed = @json($porRed);
        const ctxRed = document.getElementById('chartRed').getContext('2d');

        const redColors = [
            'rgba(236, 72, 153, 0.9)',     // Pink (Instagram)
            'rgba(59, 130, 246, 0.9)',     // Blue (Facebook)
            'rgba(139, 92, 246, 0.9)',     // Purple (Otra)
            'rgba(6, 182, 212, 0.9)',      // Cyan (Twitter/Linkedin)
            'rgba(245, 158, 11, 0.9)',     // Amber (Otra)
            'rgba(239, 68, 68, 0.9)',      // Red (Otra)
            'rgba(16, 185, 129, 0.9)'      // Green (Whatsapp)
        ];

        new Chart(ctxRed, {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataRed),
                datasets: [{
                    data: Object.values(dataRed),
                    backgroundColor: redColors.slice(0, Object.keys(dataRed).length),
                    borderColor: '#fff',
                    borderWidth: 4, // Aumentado
                    hoverBorderWidth: 6, // Aumentado
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                weight: 'bold',
                                size: 13
                            },
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        color: '#fff',
                        formatter: function(value, context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return value > 0 ? value + '\n(' + percentage + '%)' : '';
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#fff',
                        borderWidth: 2,
                        padding: 12,
                        titleFont: { weight: 'bold', size: 12 },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>