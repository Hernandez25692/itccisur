<x-app-layout>

    @include('rrhh.vacaciones.partials.estilos')

    <div class="vac-page">
        <div class="vac-container">

            {{-- Breadcrumb --}}
            <nav class="vac-breadcrumb">
                <a href="{{ route('dashboard') }}">Inicio</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <a href="{{ route('vacaciones.index') }}">Control de Vacaciones</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <a href="{{ route('vacaciones.historial', $empleado) }}">{{ $empleado->nombre_completo }}</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <span>Editar movimiento</span>
            </nav>

            @include('rrhh.vacaciones.partials.alertas')

            <div class="vac-card">

                {{-- Header --}}
                <div class="vac-header">
                    <div class="vac-header-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <div class="vac-header-main">
                        <h1 class="vac-header-title">Editar movimiento</h1>
                        <p class="vac-header-sub">
                            <span class="vac-employee-name">{{ $empleado->nombre_completo }}</span> ·
                            {{ $movimiento->descripcion() }}
                        </p>
                    </div>
                </div>

                <div class="vac-body">
                    <div class="vac-alert">
                        Los cambios recalculan el saldo y quedan registrados en la bitácora con el valor anterior y el
                        nuevo. Si el movimiento no debió registrarse, anúlelo desde el historial.
                    </div>

                    <div class="vac-section">
                        <div class="vac-section-label">Saldo actual del empleado</div>
                        @include('rrhh.vacaciones.partials.resumen_saldo')
                    </div>

                    <form method="POST" action="{{ route('vacaciones.actualizar', $movimiento) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('rrhh.vacaciones.partials.form_movimiento')

                        <div class="vac-footer">
                            <a href="{{ route('vacaciones.historial', $empleado) }}" class="vac-btn-cancel">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" class="vac-btn-save">
                                <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Actualizar movimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
