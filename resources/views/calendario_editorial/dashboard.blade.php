<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-10">

        <!-- ENCABEZADO -->
        <div class="flex flex-col lg:flex-row lg:justify-between gap-4 items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">
                    📊 Dashboard – Calendario Editorial
                </h1>
                <p class="text-gray-500 mt-1">Análisis detallado de publicaciones</p>
            </div>

            <div class="flex gap-3 items-end flex-wrap">
                <a href="{{ route('calendario-editorial.index') }}"
                    class="px-5 py-2.5 bg-gradient-to-r from-[#0C1C3C] to-[#1a2e5c] text-white rounded-lg text-sm font-semibold hover:shadow-lg transition">
                    📅 Ir al Calendario
                </a>

                <form method="GET" class="flex gap-3">
                    <select name="mes" class="border-2 border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:border-blue-500 focus:outline-none">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($mes == $m)>
                                {{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->format('F') }}
                            </option>
                        @endforeach
                    </select>

                    <select name="anio" class="border-2 border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:border-blue-500 focus:outline-none">
                        @foreach (range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" @selected($anio == $y)>{{ $y }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-sm font-semibold hover:shadow-lg transition">
                        🔍 Filtrar
                    </button>
                </form>
            </div>
        </div>

        <!-- KPIs - Cards Mejoradas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-l-4 border-blue-600 p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Publicaciones</p>
                        <p class="text-4xl font-bold text-blue-700 mt-2">{{ $totalMes }}</p>
                    </div>
                    <span class="text-3xl">📈</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border-l-4 border-green-600 p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Publicadas</p>
                        <p class="text-4xl font-bold text-green-700 mt-2">{{ $porEstado['publicado'] ?? 0 }}</p>
                    </div>
                    <span class="text-3xl">✅</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border-l-4 border-orange-600 p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Pendientes</p>
                        <p class="text-4xl font-bold text-orange-700 mt-2">{{ $porEstado['pendiente'] ?? 0 }}</p>
                    </div>
                    <span class="text-3xl">⏳</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border-l-4 border-purple-600 p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Cumplimiento</p>
                        <p class="text-4xl font-bold text-purple-700 mt-2">{{ $cumplimiento }}%</p>
                    </div>
                    <span class="text-3xl">🎯</span>
                </div>
            </div>

        </div>

        <!-- GRÁFICAS - Con colores fuertes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-600">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    📊 Publicaciones por Semana
                </h3>
                <div class="relative h-80">
                    <canvas id="chartSemana"></canvas>
                </div>
                <div class="mt-4 p-3 bg-blue-50 rounded-lg text-sm text-gray-700">
                    <span class="font-semibold">Total semanas: </span> <span id="totalSemanas" class="text-blue-700 font-bold">0</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-600">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    📅 Publicaciones por Día
                </h3>
                <div class="relative h-80">
                    <canvas id="chartDia"></canvas>
                </div>
                <div class="mt-4 p-3 bg-green-50 rounded-lg text-sm text-gray-700">
                    <span class="font-semibold">Promedio diario: </span> <span id="promedioDiario" class="text-green-700 font-bold">0</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-purple-600 lg:col-span-2">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    🌐 Publicaciones por Medio
                </h3>
                <div class="relative h-80">
                    <canvas id="chartRed"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div id="indicesRed"></div>
                </div>
            </div>
        </div>

        <!-- TABLA: POR DÍA - Estilo Excel Dinámico -->
        <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-orange-600">
            <h3 class="font-bold text-lg text-gray-800 mb-6 flex items-center gap-2">
                📆 Publicaciones por Día de la Semana
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold">
                            <th class="px-4 py-3 text-left border border-orange-700">Semana</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Lunes</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Martes</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Miércoles</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Jueves</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Viernes</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Sábado</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Domingo</th>
                            <th class="px-4 py-3 text-center border border-orange-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablaPorDiaSemana as $index => $fila)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white hover:bg-orange-50' : 'bg-gray-50 hover:bg-orange-50' }} transition border-b border-gray-200">
                                <td class="px-4 py-3 font-semibold text-gray-800 border border-gray-300">{{ $fila['semana'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Lunes'] > 0 ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Lunes'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Martes'] > 0 ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Martes'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Miércoles'] > 0 ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Miércoles'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Jueves'] > 0 ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Jueves'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Viernes'] > 0 ? 'bg-green-100 text-green-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Viernes'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Sábado'] > 0 ? 'bg-yellow-100 text-yellow-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Sábado'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['Domingo'] > 0 ? 'bg-red-100 text-red-900 font-semibold' : 'text-gray-500' }}">{{ $fila['Domingo'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 bg-purple-200 text-purple-900 font-bold">
                                    {{ $fila['Lunes'] + $fila['Martes'] + $fila['Miércoles'] + $fila['Jueves'] + $fila['Viernes'] + $fila['Sábado'] + $fila['Domingo'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABLA: CONTENIDO - Estilo Excel Dinámico -->
        <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-indigo-600">
            <h3 class="font-bold text-lg text-gray-800 mb-6 flex items-center gap-2">
                📝 Contenido por Semana
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold">
                            <th class="px-4 py-3 text-left border border-indigo-700">Semana</th>
                            <th class="px-4 py-3 text-center border border-indigo-700">
                                <span title="Vivencias">🎬 VIV</span>
                            </th>
                            <th class="px-4 py-3 text-center border border-indigo-700">
                                <span title="Imágenes">🖼️ IMG</span>
                            </th>
                            <th class="px-4 py-3 text-center border border-indigo-700">
                                <span title="Carousel">🎠 CAR</span>
                            </th>
                            <th class="px-4 py-3 text-center border border-indigo-700">
                                <span title="Historia">📖 HIST</span>
                            </th>
                            <th class="px-4 py-3 text-center border border-indigo-700">
                                <span title="Videos">🎥 VID</span>
                            </th>
                            <th class="px-4 py-3 text-center border border-indigo-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablaContenidoPorSemana as $index => $fila)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white hover:bg-indigo-50' : 'bg-gray-50 hover:bg-indigo-50' }} transition border-b border-gray-200">
                                <td class="px-4 py-3 font-semibold text-gray-800 border border-gray-300">{{ $fila['semana'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['VIV'] > 0 ? 'bg-red-100 text-red-900 font-semibold' : 'text-gray-500' }}">{{ $fila['VIV'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['IMG'] > 0 ? 'bg-purple-100 text-purple-900 font-semibold' : 'text-gray-500' }}">{{ $fila['IMG'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['CAR'] > 0 ? 'bg-cyan-100 text-cyan-900 font-semibold' : 'text-gray-500' }}">{{ $fila['CAR'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['HIST'] > 0 ? 'bg-yellow-100 text-yellow-900 font-semibold' : 'text-gray-500' }}">{{ $fila['HIST'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 {{ $fila['VID'] > 0 ? 'bg-pink-100 text-pink-900 font-semibold' : 'text-gray-500' }}">{{ $fila['VID'] }}</td>
                                <td class="px-4 py-3 text-center border border-gray-300 bg-indigo-200 text-indigo-900 font-bold">
                                    {{ $fila['VIV'] + $fila['IMG'] + $fila['CAR'] + $fila['HIST'] + $fila['VID'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Colores fuertes y vibrantes
        const colores = {
            primary: '#1e40af',
            success: '#16a34a',
            warning: '#ea580c',
            danger: '#dc2626',
            purple: '#7c3aed',
            cyan: '#0891b2',
            pink: '#ec4899',
            indigo: '#4f46e5'
        };

        // Gráfica Semana
        const dataSemana = @json($porSemana->pluck('total'));
        new Chart(document.getElementById('chartSemana'), {
            type: 'bar',
            data: {
                labels: @json($porSemana->pluck('semana')->map(fn($s) => 'Semana ' . $s)),
                datasets: [{
                    label: 'Publicaciones',
                    data: dataSemana,
                    backgroundColor: 'rgba(30, 64, 175, 0.8)',
                    borderColor: '#1e40af',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(30, 64, 175, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { weight: 'bold' } } }
                }
            }
        });
        document.getElementById('totalSemanas').textContent = dataSemana.reduce((a,b) => a+b, 0);

        // Gráfica Día
        const dataDia = @json($porDia->pluck('total'));
        new Chart(document.getElementById('chartDia'), {
            type: 'bar',
            data: {
                labels: @json($porDia->pluck('dia')),
                datasets: [{
                    label: 'Publicaciones',
                    data: dataDia,
                    backgroundColor: 'rgba(22, 163, 74, 0.8)',
                    borderColor: '#16a34a',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(22, 163, 74, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { weight: 'bold' } } }
                }
            }
        });
        const promedio = Math.round(dataDia.reduce((a,b) => a+b, 0) / dataDia.length);
        document.getElementById('promedioDiario').textContent = promedio;

        // Gráfica Red
        const dataRed = @json($porRed);
        const coloresRed = ['#ec4899', '#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b', '#ef4444', '#10b981'];
        new Chart(document.getElementById('chartRed'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataRed),
                datasets: [{
                    data: Object.values(dataRed),
                    backgroundColor: coloresRed.slice(0, Object.keys(dataRed).length),
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverBorderWidth: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { weight: 'bold', size: 12 }, padding: 15 } }
                }
            }
        });

        // Índices por Red
        const indicesHTML = Object.entries(dataRed).map((item, i) => `
            <div class="p-3 rounded-lg" style="background-color: ${coloresRed[i]}20; border-left: 4px solid ${coloresRed[i]}">
                <p class="text-xs font-semibold text-gray-600">${item[0]}</p>
                <p class="text-xl font-bold" style="color: ${coloresRed[i]}">${item[1]}</p>
            </div>
        `).join('');
        document.getElementById('indicesRed').innerHTML = indicesHTML;
    </script>
</x-app-layout>
