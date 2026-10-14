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
                <span>Registrar</span>
            </nav>

            @include('rrhh.vacaciones.partials.alertas')

            {{-- Card --}}
            <div class="vac-card">

                {{-- Header --}}
                <div class="vac-header">
                    <div class="vac-header-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            <circle cx="12" cy="15" r="2" />
                        </svg>
                    </div>
                    <div class="vac-header-main">
                        <h1 class="vac-header-title">Registrar vacaciones o permiso</h1>
                        <p class="vac-header-sub">
                            Para: <span class="vac-employee-name">{{ $empleado->nombre_completo }}</span> ·
                            Ingreso {{ $empleado->fecha_contratacion->format('d-m-Y') }} ·
                            {{ $empleado->aniosServicio() }} año(s) de servicio
                        </p>
                    </div>
                </div>

                <div class="vac-body">
                    <div class="vac-section">
                        <div class="vac-section-label">Saldo del empleado</div>
                        @include('rrhh.vacaciones.partials.resumen_saldo')
                    </div>

                    <form method="POST" action="{{ route('vacaciones.guardar') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="empleado_id" value="{{ $empleado->id }}">

                        @include('rrhh.vacaciones.partials.form_movimiento')

                        {{-- Footer --}}
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
                                Guardar solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
