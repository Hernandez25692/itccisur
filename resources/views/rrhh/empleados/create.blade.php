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
            <span>Nuevo registro</span>
        </nav>

        {{-- Card --}}
        <div class="emp-card">

            {{-- Header --}}
            <div class="emp-header">
                <div class="emp-header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3M13.5 4.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM3 19.5a7.5 7.5 0 0 1 15 0" />
                    </svg>
                </div>
                <div>
                    <h1 class="emp-header-title">Registrar nuevo empleado</h1>
                    <p class="emp-header-sub">Complete todos los campos requeridos para crear el registro.</p>
                </div>
            </div>

            {{-- Form --}}
            <div class="emp-body">
                <form method="POST" action="{{ route('empleados.store') }}">
                    @csrf

                    @include('rrhh.empleados.partials.form')

                    {{-- Footer --}}
                    <div class="emp-footer">
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
                            Guardar empleado
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</x-app-layout>
