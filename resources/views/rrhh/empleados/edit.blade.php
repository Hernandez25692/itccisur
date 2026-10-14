<x-app-layout>

    @include('rrhh.empleados.partials.estilos')

    <div class="emp-page">

        {{-- Breadcrumb --}}
        <nav class="emp-breadcrumb">
            <a href="{{ route('dashboard') }}">Inicio</a>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <a href="{{ route('empleados.index') }}">Empleados</a>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <span>Editar empleado</span>
        </nav>

        {{-- Card --}}
        <div class="emp-card">

            {{-- Header --}}
            <div class="emp-header">
                <div class="emp-header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h1 class="emp-header-title">Editar empleado</h1>
                    <p class="emp-header-sub">
                        {{ $empleado->nombre_completo }} ·
                        {{ $empleado->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                    </p>
                </div>
            </div>

            {{-- Form --}}
            <div class="emp-body">
                <form method="POST" action="{{ route('empleados.update', $empleado) }}">
                    @csrf
                    @method('PUT')

                    @include('rrhh.empleados.partials.form')

                    {{-- Footer --}}
                    <div class="emp-footer">
                        <a href="{{ route('vacaciones.historial', $empleado) }}" class="emp-btn-cancel">
                            Ver vacaciones
                        </a>
                        <a href="{{ route('empleados.index') }}" class="emp-btn-cancel">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="emp-btn-save">
                            <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</x-app-layout>
