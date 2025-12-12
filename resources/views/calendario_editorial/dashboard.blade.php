<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 space-y-10">

        <!-- ENCABEZADO -->
        <div class="flex flex-col lg:flex-row lg:justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-800">
                📊 Dashboard – Calendario Editorial
            </h1>

            <div class="flex gap-2 items-end">
                <a href="{{ route('calendario-editorial.index') }}"
                    class="px-4 py-2 bg-[#0C1C3C] text-white rounded-lg text-sm">
                    📅 Ir al Calendario
                </a>

                <form method="GET" class="flex gap-2">
                    <select name="mes" class="border rounded p-2 text-sm">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($mes == $m)>
                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="anio" class="border rounded p-2 text-sm">
                        @foreach (range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" @selected($anio == $y)>{{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total publicaciones</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalMes }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">Publicadas</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $porEstado['publicado'] ?? 0 }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">Pendientes</p>
                <p class="text-3xl font-bold text-orange-600">
                    {{ $porEstado['pendiente'] ?? 0 }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">% Cumplimiento</p>
                <p class="text-3xl font-bold text-blue-600">
                    {{ $cumplimiento }}%
                </p>
            </div>

        </div>

        <!-- GRÁFICAS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="font-semibold mb-2">Publicaciones por Semana</h3>
                <canvas id="chartSemana"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="font-semibold mb-2">Publicaciones por Día</h3>
                <canvas id="chartDia"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow lg:col-span-2">
                <h3 class="font-semibold mb-2">Publicaciones por Medio</h3>
                <canvas id="chartRed"></canvas>
            </div>
        </div>

        <!-- TABLA: POR DÍA -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Publicaciones por Día de la Semana</h3>
            <table class="w-full text-sm text-center border">
                <thead class="bg-gray-100">
                    <tr>
                        <th>Semana</th>
                        <th>L</th>
                        <th>M</th>
                        <th>X</th>
                        <th>J</th>
                        <th>V</th>
                        <th>Sá.</th>
                        <th>Do.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tablaPorDiaSemana as $fila)
                        <tr>
                            <td>{{ $fila['semana'] }}</td>
                            <td>{{ $fila['Lunes'] }}</td>
                            <td>{{ $fila['Martes'] }}</td>
                            <td>{{ $fila['Miércoles'] }}</td>
                            <td>{{ $fila['Jueves'] }}</td>
                            <td>{{ $fila['Viernes'] }}</td>
                            <td>{{ $fila['Sábado'] }}</td>
                            <td>{{ $fila['Domingo'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TABLA: CONTENIDO -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Contenido por Semana</h3>
            <table class="w-full text-sm text-center border">
                <thead class="bg-gray-100">
                    <tr>
                        <th>Semana</th>
                        <th>VIV</th>
                        <th>IMG</th>
                        <th>CAR</th>
                        <th>HIST</th>
                        <th>VID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tablaContenidoPorSemana as $fila)
                        <tr>
                            <td>{{ $fila['semana'] }}</td>
                            <td>{{ $fila['VIV'] }}</td>
                            <td>{{ $fila['IMG'] }}</td>
                            <td>{{ $fila['CAR'] }}</td>
                            <td>{{ $fila['HIST'] }}</td>
                            <td>{{ $fila['VID'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(chartSemana, {
            type: 'bar',
            data: {
                labels: @json($porSemana->pluck('semana')->map(fn($s) => 'S ' . $s)),
                datasets: [{
                    data: @json($porSemana->pluck('total'))
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(chartDia, {
            type: 'bar',
            data: {
                labels: @json($porDia->pluck('dia')),
                datasets: [{
                    data: @json($porDia->pluck('total'))
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(chartRed, {
            type: 'doughnut',
            data: {
                labels: @json($porRed->keys()),
                datasets: [{
                    data: @json($porRed->values())
                }]
            }
        });
    </script>
</x-app-layout>
