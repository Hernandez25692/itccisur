<x-app-layout>

    <h2 class="text-3xl font-bold mb-6 text-gray-800">Dashboard TI – Resumen Gerencial</h2>

    {{-- Tarjetas resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white shadow rounded-xl p-6 border-l-8 border-blue-500">
            <h3 class="text-gray-600">Actividades Hoy</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $actividadesHoy }}</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6 border-l-8 border-yellow-500">
            <h3 class="text-gray-600">Pendientes</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $pendientes }}</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6 border-l-8 border-green-500">
            <h3 class="text-gray-600">Resueltas este mes</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $resueltasMes }}</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6 border-l-8 border-purple-500">
            <h3 class="text-gray-600">Tiempo Promedio (min)</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $tiempoPromedio }}</p>
        </div>

    </div>

    {{-- Gráficas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        <div class="bg-white p-6 rounded-xl shadow border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribución por Prioridad</h3>
            <canvas id="prioridadesChart"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Fallas por Tipo</h3>
            <canvas id="fallasChart"></canvas>
        </div>

    </div>

    {{-- Línea de tiempo mensual --}}
    <div class="bg-white p-6 rounded-xl shadow border mb-10">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividades por Día del Mes</h3>
        <canvas id="porDiaChart"></canvas>
    </div>

    {{-- Últimas actividades --}}
    <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Actividades</h3>

        <div class="space-y-4">
            @foreach ($ultimas as $a)
                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <h4 class="font-bold">{{ $a->titulo }}</h4>
                    <p class="text-gray-600 text-sm">{{ $a->fecha->format('d/m/Y') }} — {{ $a->user->name }}</p>
                    <p class="text-gray-700 mt-1">{{ $a->descripcion }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Prioridades
        new Chart(document.getElementById('prioridadesChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($prioridades->keys()) !!},
                datasets: [{
                    data: {!! json_encode($prioridades->values()) !!},
                    backgroundColor: ['#22c55e', '#eab308', '#ef4444', '#7e22ce']
                }]
            }
        });

        // Fallas por tipo
        new Chart(document.getElementById('fallasChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($fallasPorTipo->keys()) !!},
                datasets: [{
                    label: 'Cantidad',
                    data: {!! json_encode($fallasPorTipo->values()) !!},
                    backgroundColor: '#2563eb'
                }]
            }
        });

        // Por día
        new Chart(document.getElementById('porDiaChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($porDia->keys()) !!},
                datasets: [{
                    label: 'Actividades',
                    data: {!! json_encode($porDia->values()) !!},
                    backgroundColor: '#1e40af',
                    borderColor: '#1e3a8a'
                }]
            }
        });
    </script>

</x-app-layout>
