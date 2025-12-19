<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
        }

        .antecedentes-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .antecedentes-container {
                padding: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .antecedentes-container {
                padding: 2rem;
            }
        }

        .antecedentes-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 640px) {
            .page-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .page-title {
            color: var(--primary-dark);
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        @media (min-width: 768px) {
            .page-title {
                font-size: 1.75rem;
            }
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-new {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-light) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .btn-new:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(197, 160, 73, 0.3);
        }

        /* Filtros */
        .filters-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 640px) {
            .filters-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .filters-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .filter-input,
        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #374151;
            background: white;
            transition: all 0.2s;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.1);
        }

        .filters-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: space-between;
            align-items: stretch;
        }

        @media (min-width: 640px) {
            .filters-actions {
                flex-direction: row;
                align-items: center;
            }
        }

        .btn-clear {
            color: #6b7280;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            color: var(--primary-dark);
            background: #f9fafb;
        }

        .btn-filter {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 42, 79, 0.3);
        }

        /* Listado */
        .registros-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .registro-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: hidden;
        }

        .registro-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .registro-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--gold-primary), var(--gold-light));
        }

        .registro-header {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        @media (min-width: 640px) {
            .registro-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }
        }

        .registro-info {
            flex: 1;
        }

        .registro-nombre {
            color: var(--primary-dark);
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }

        .registro-circunscripcion {
            color: #6b7280;
            font-size: 0.875rem;
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .registro-fecha {
            color: #9ca3af;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .registro-details {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        @media (min-width: 640px) {
            .registro-details {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-label {
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            color: #374151;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .registro-footer {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (min-width: 640px) {
            .registro-footer {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .registro-creador {
            color: #6b7280;
            font-size: 0.75rem;
        }

        .creador-name {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .registro-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view {
            color: #6b7280;
            border: 1px solid #e5e7eb;
            background: white;
        }

        .btn-view:hover {
            background: #f9fafb;
            border-color: var(--gold-light);
            color: var(--gold-primary);
        }

        .btn-edit {
            color: var(--primary-dark);
            border: 1px solid var(--gold-light);
            background: rgba(197, 160, 73, 0.1);
            font-weight: 500;
        }

        .btn-edit:hover {
            background: rgba(197, 160, 73, 0.2);
            border-color: var(--gold-primary);
        }

        /* Paginación */
        .pagination-container {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        /* Empty state */
        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            color: var(--primary-dark);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        /* Safari specific fixes */
        @media not all and (min-resolution:.001dpcm) {
            @supports (-webkit-appearance:none) {

                .filter-input,
                .filter-select {
                    -webkit-appearance: none;
                }
            }
        }

        /* Firefox scrollbar */
        @-moz-document url-prefix() {
            .registro-card {
                scrollbar-width: thin;
                scrollbar-color: var(--gold-light) #f1f1f1;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {

            .btn-new,
            .btn-filter,
            .btn-action {
                min-height: 44px;
                min-width: 44px;
            }

            .filter-input,
            .filter-select {
                font-size: 16px;
                /* Prevents iOS zoom */
            }
        }
    </style>

    <div class="antecedentes-container">
        <div class="antecedentes-wrapper">
            <!-- ENCABEZADO -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Revisión de Antecedentes Registrales</h1>
                    <p class="page-subtitle">Centro Asociado del Sur</p>
                </div>

                <a href="{{ route('gor.antecedentes.create') }}" class="btn-new">
                    + Nuevo Registro
                </a>
            </div>

            <!-- 🔎 FILTROS -->
            <form method="GET" class="filters-container">
                <div class="filters-grid">
                    <!-- Circunscripción -->
                    <select name="circunscripcion" class="filter-select">
                        <option value="">Todas las circunscripciones</option>
                        <option value="Nacaome - Valle"
                            {{ request('circunscripcion') == 'Nacaome - Valle' ? 'selected' : '' }}>
                            Nacaome / Valle
                        </option>
                        <option value="Choluteca - Choluteca"
                            {{ request('circunscripcion') == 'Choluteca - Choluteca' ? 'selected' : '' }}>
                            Choluteca / Choluteca
                        </option>
                    </select>

                    <!-- Fecha desde -->
                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="filter-input"
                        placeholder="Desde">

                    <!-- Fecha hasta -->
                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="filter-input"
                        placeholder="Hasta">

                    <!-- Texto libre -->
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="filter-input"
                        placeholder="Nombre o Exequátur">
                </div>

                <div class="filters-actions">
                    <a href="{{ route('gor.antecedentes.index') }}" class="btn-clear">
                        Limpiar filtros
                    </a>

                    <button type="submit" class="btn-filter">
                        🔍 Filtrar resultados
                    </button>
                </div>
            </form>

            <!-- LISTADO -->
            @if ($registros->count() > 0)
                <div class="registros-list">
                    @foreach ($registros as $registro)
                        <div class="registro-card">
                            <div class="registro-header">
                                <div class="registro-info">
                                    <h3 class="registro-nombre">
                                        {{ $registro->solicitante_nombre }}
                                    </h3>
                                    <span class="registro-circunscripcion">
                                        {{ $registro->circunscripcion }}
                                    </span>
                                </div>

                                <span class="registro-fecha">
                                    {{ $registro->created_at->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="registro-details">
                                <div class="detail-item">
                                    <span class="detail-label">Fecha de Recepción</span>
                                    <span class="detail-value">
                                        {{ \Carbon\Carbon::parse($registro->fecha_recepcion)->format('d/m/Y') }}
                                    </span>
                                </div>

                                @if ($registro->numero_exequatur)
                                    <div class="detail-item">
                                        <span class="detail-label">Número de Exequátur</span>
                                        <span class="detail-value">
                                            {{ $registro->numero_exequatur }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="registro-footer">
                                <div class="registro-creador">
                                    Registrado por:
                                    <span class="creador-name">
                                        {{ $registro->creador->name ?? 'N/D' }}
                                    </span>
                                </div>

                                <div class="registro-actions">
                                    <a href="{{ route('gor.antecedentes.show', $registro->id) }}"
                                        class="btn-action btn-view">
                                        Ver detalles
                                    </a>
                                    <a href="{{ route('gor.antecedentes.edit', $registro->id) }}"
                                        class="btn-action btn-edit">
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="pagination-container">
                    <div class="pagination-wrapper">
                        {{ $registros->links() }}
                    </div>
                </div>
            @else
                <!-- Estado vacío -->
                <div class="empty-state">
                    <div class="empty-icon">📄</div>
                    <h3 class="empty-title">No se encontraron registros</h3>
                    <p class="empty-text">
                        {{ request()->hasAny(['circunscripcion', 'fecha_desde', 'fecha_hasta', 'buscar'])
                            ? 'Intenta con otros filtros de búsqueda.'
                            : 'Comienza creando un nuevo registro.' }}
                    </p>
                    <a href="{{ route('gor.antecedentes.create') }}" class="btn-new">
                        + Crear primer registro
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
