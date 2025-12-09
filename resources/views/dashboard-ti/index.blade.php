<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --primary-light: #2A3A6F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
            --gold-dark: #B59038;
            --text-dark: #1F2937;
            --text-medium: #4B5563;
            --text-light: #6B7280;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --glass-bg: rgba(255, 255, 255, 0.92);
            --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .dashboard-container {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            min-height: 100vh;
            padding: 1.5rem;
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .dashboard-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 400;
        }

        /* Alertas */
        .alert-container {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(239, 68, 68, 0.08) 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--danger);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-subtle);
        }

        .alert-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .alert-icon {
            background: var(--danger);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
        }

        .alert-title {
            color: var(--text-dark);
            font-size: 1.125rem;
            font-weight: 600;
        }

        .alert-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 0.75rem;
        }

        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            color: var(--text-dark);
            font-size: 0.95rem;
            line-height: 1.4;
            padding: 0.5rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.6);
            transition: all 0.2s ease;
        }

        .alert-item:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: translateX(2px);
        }

        .alert-item::before {
            content: "•";
            color: var(--danger);
            font-weight: bold;
            font-size: 1.2rem;
            line-height: 1;
        }

        .alert-type {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            margin-left: 0.5rem;
        }

        .alert-date {
            color: var(--danger);
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Tarjetas de métricas */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .metric-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color, var(--info));
        }

        .metric-label {
            color: var(--text-medium);
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            letter-spacing: 0.025em;
        }

        .metric-value {
            color: var(--text-dark);
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .metric-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        /* Gráficas */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .chart-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            color: var(--text-dark);
            font-size: 1.125rem;
            font-weight: 600;
        }

        .chart-legend {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: var(--text-medium);
        }

        .legend-color {
            width: 10px;
            height: 10px;
            border-radius: 2px;
        }

        /* Timeline */
        .timeline-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
        }

        .timeline-header {
            margin-bottom: 1.5rem;
        }

        .timeline-title {
            color: var(--text-dark);
            font-size: 1.125rem;
            font-weight: 600;
        }

        /* Actividades recientes */
        .activities-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .activities-header {
            margin-bottom: 1.5rem;
        }

        .activities-title {
            color: var(--text-dark);
            font-size: 1.125rem;
            font-weight: 600;
        }

        .activities-list {
            display: grid;
            gap: 1rem;
        }

        .activity-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .activity-item:hover {
            border-color: var(--gold-light);
            box-shadow: 0 4px 12px rgba(197, 160, 73, 0.1);
            transform: translateY(-2px);
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: -1px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--gold-primary);
            border-radius: 0 2px 2px 0;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .activity-item:hover::before {
            opacity: 1;
        }

        .activity-title {
            color: var(--text-dark);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .activity-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .activity-date {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .activity-user {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .activity-description {
            color: var(--text-medium);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .metric-value {
                font-size: 1.875rem;
            }
        }

        /* Color utility classes */
        .border-gold {
            border-color: var(--gold-primary);
        }

        .text-gold {
            color: var(--gold-primary);
        }

        .bg-gold {
            background: var(--gold-primary);
        }

        .border-primary {
            border-color: var(--primary-medium);
        }

        .text-primary {
            color: var(--primary-medium);
        }

        .bg-primary {
            background: var(--primary-medium);
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard TI – Resumen Gerencial</h1>
            <p class="dashboard-subtitle">Panel de control del Departamento de Sistemas y TI - CCISUR</p>
        </div>

        {{-- 🔔 Alertas de vencimiento de licencias / dominios / servicios --}}
        @if ($alertas->count() > 0)
            <div class="alert-container">
            <div class="alert-header">
                <div class="alert-icon">
                ⚠️
                </div>
                <h2 class="alert-title" style="color: white;">Alertas de vencimiento (próximos 15 días)</h2>
            </div>

            <ul class="alert-list">
                @foreach ($alertas as $alerta)
                <li class="alert-item" style="color: white; background: rgba(255, 255, 255, 0.15);">
                    <strong>{{ $alerta->actividad }}</strong>
                    @if ($alerta->tipo)
                    <span class="alert-type">{{ $alerta->tipo }}</span>
                    @endif
                    — vence el
                    <span class="alert-date">
                    {{ \Carbon\Carbon::parse($alerta->fecha_vencimiento)->format('d/m/Y') }}
                    </span>
                </li>
                @endforeach
            </ul>
            </div>
        @endif

        {{-- Tarjetas resumen --}}
        <div class="metrics-grid">
            <div class="metric-card" style="--accent-color: #3B82F6;">
                <h3 class="metric-label">Actividades Hoy</h3>
                <p class="metric-value">{{ $actividadesHoy }}</p>
                <div class="metric-trend">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                            clip-rule="evenodd" />
                    </svg>
                    Hoy
                </div>
            </div>

            <div class="metric-card" style="--accent-color: #F59E0B;">
                <h3 class="metric-label">Pendientes</h3>
                <p class="metric-value">{{ $pendientes }}</p>
                <div class="metric-trend" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    Requiere atención
                </div>
            </div>

            <div class="metric-card" style="--accent-color: #10B981;">
                <h3 class="metric-label">Resueltas este mes</h3>
                <p class="metric-value">{{ $resueltasMes }}</p>
                <div class="metric-trend">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Completadas
                </div>
            </div>

            
        </div>

        {{-- Gráficas --}}
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Distribución por Prioridad</h3>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #22c55e;"></div>
                            <span>Baja</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #eab308;"></div>
                            <span>Media</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #ef4444;"></div>
                            <span>Alta</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #7e22ce;"></div>
                            <span>Crítica</span>
                        </div>
                    </div>
                </div>
                <canvas id="prioridadesChart"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Fallas por Tipo</h3>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #2563eb;"></div>
                            <span>Incidencias</span>
                        </div>
                    </div>
                </div>
                <canvas id="fallasChart"></canvas>
            </div>
        </div>

        {{-- Línea de tiempo mensual --}}
        <div class="timeline-container">
            <div class="timeline-header">
                <h3 class="timeline-title">Actividades por Día del Mes</h3>
            </div>
            <canvas id="porDiaChart"></canvas>
        </div>

        {{-- Últimas actividades --}}
        <div class="activities-container">
            <div class="activities-header">
                <h3 class="activities-title">Últimas Actividades</h3>
            </div>

            <div class="activities-list">
                @foreach ($ultimas as $a)
                    <div class="activity-item">
                        <h4 class="activity-title">{{ $a->titulo }}</h4>
                        <div class="activity-meta">
                            <span class="activity-date">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $a->fecha->format('d/m/Y') }}
                            </span>
                            <span class="activity-user">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $a->user->name }}
                            </span>
                        </div>
                        <p class="activity-description">{{ $a->descripcion }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Prioridades - Doughnut con estilo institucional
        new Chart(document.getElementById('prioridadesChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($prioridades->keys()) !!},
                datasets: [{
                    data: {!! json_encode($prioridades->values()) !!},
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(126, 34, 206, 0.8)'
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(126, 34, 206, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 42, 79, 0.9)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: '#C5A049',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 6
                    }
                },
                cutout: '70%'
            }
        });

        // Fallas por tipo - Barra con estilo institucional
        new Chart(document.getElementById('fallasChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($fallasPorTipo->keys()) !!},
                datasets: [{
                    label: 'Cantidad',
                    data: {!! json_encode($fallasPorTipo->values()) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(197, 160, 73, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 42, 79, 0.9)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: '#C5A049',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    }
                }
            }
        });

        // Por día - Línea con estilo Excel avanzado
        new Chart(document.getElementById('porDiaChart'), {
            type: 'line',
            data: {
            labels: {!! json_encode($porDia->keys()) !!},
            datasets: [{
                label: 'Actividades',
                data: {!! json_encode($porDia->values()) !!},
                backgroundColor: 'rgba(197, 160, 73, 0.15)',
                borderColor: '#C5A049',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#C5A049',
                pointBorderColor: 'white',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 9,
                pointStyle: 'circle',
                segment: {
                borderDash: [0]
                }
            }]
            },
            options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                display: true,
                position: 'top',
                labels: {
                    color: '#1F2937',
                    font: {
                    weight: 600,
                    size: 12
                    },
                    padding: 15,
                    usePointStyle: true
                }
                },
                tooltip: {
                backgroundColor: 'rgba(26, 42, 79, 0.95)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: '#C5A049',
                borderWidth: 2,
                padding: 14,
                cornerRadius: 8,
                titleFont: {
                    weight: 600,
                    size: 13
                },
                bodyFont: {
                    size: 12
                },
                displayColors: true,
                boxPadding: 6
                }
            },
            scales: {
                y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.08)',
                    lineWidth: 1,
                    drawBorder: true
                },
                ticks: {
                    color: '#4B5563',
                    font: {
                    size: 11,
                    weight: 500
                    },
                    padding: 8
                },
                border: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    lineWidth: 1
                }
                },
                x: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    lineWidth: 1
                },
                ticks: {
                    color: '#4B5563',
                    font: {
                    size: 11,
                    weight: 500
                    },
                    padding: 8
                },
                border: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    lineWidth: 1
                }
                }
            }
            }
        });
    </script>

</x-app-layout>
