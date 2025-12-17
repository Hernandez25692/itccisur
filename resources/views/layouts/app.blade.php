<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CCISUR TI – Sistema de Tecnología</title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gradient-to-b from-slate-50 via-white to-slate-100 text-gray-800">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

        {{-- Sidebar Vertical Fijo --}}
        <aside
            class="w-64 bg-gradient-to-b from-[#0C1C3C] to-[#1A2A4F] shadow-2xl flex flex-col transition-all duration-300 overflow-y-auto"
            :class="{ 'hidden': !sidebarOpen }">

            {{-- Logo/Branding --}}
            <div class="px-6 py-4 border-b border-blue-900/30 bg-gradient-to-r from-blue-900/40 to-transparent">
                <div class="flex flex-col items-center justify-center space-y-1">
                    <div class="p-3 bg-[#C5A049]/20 rounded-lg">
                        <img src="{{ asset('storage/logos/logo2.png') }}" alt="CCISUR logo" class="h-14 w-auto">
                    </div>
                    <div class="text-center">
                        <h1 class="text-white font-bold text-lg leading-tight">CCISUR-TI</h1>
                        <p class="text-blue-300 text-xs leading-tight">Gestión Integrada</p>
                    </div>
                </div>
            </div>

            {{-- Menú de Navegación --}}
            <nav class="flex-1 px-4 py-6 space-y-1">

                {{-- Sección Principal --}}
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide">Principal</p>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                        <i class="fas fa-home w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                        @if (request()->routeIs('dashboard'))
                            <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                        @endif
                    </a>
                    @role('admin_ti|gerencia|usuario|calendario')
                        @if (Route::has('calendario-editorial.dashboard'))
                            <a href="{{ route('calendario-editorial.dashboard') }}"
                                class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200
   {{ request()->routeIs('calendario-editorial.dashboard') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">

                                <i class="fas fa-chart-line w-5 text-lg"></i>

                                <span class="ml-3 font-medium">
                                    Resumen Calendario
                                </span>

                                @if (request()->routeIs('calendario-editorial.dashboard'))
                                    <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                                @endif
                            </a>
                        @endif
                    @endrole

                    @role('admin_ti|gerencia')
                        <a href="{{ route('dashboard.ti') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.ti') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-chart-line w-5 text-lg"></i>
                            <span class="ml-3 font-medium">Dashboard TI</span>
                            @if (request()->routeIs('dashboard.ti'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                            @endif
                        </a>
                    @endrole
                </div>

                {{-- Sección Gestión --}}
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide">Gestión</p>
                    @role('admin_ti|gerencia')
                        <a href="{{ route('plan-trabajo.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('plan-trabajo.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-list-check w-5 text-lg"></i>
                            <span class="ml-3 font-medium">Plan de Trabajo TI</span>
                            @if (request()->routeIs('plan-trabajo.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                            @endif
                        </a>
                    @endrole

                    <a href="{{ route('calendario-editorial.index') }}"
                        class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200
   {{ request()->routeIs('calendario-editorial.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">

                        <i class="fas fa-calendar-alt w-5 text-lg"></i>

                        <span class="ml-3 font-medium">
                            Calendario
                        </span>

                        @if (request()->routeIs('calendario-editorial.*'))
                            <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                        @endif
                    </a>

                    @role('admin_ti|gerencia')
                        <a href="{{ route('bitacora.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('bitacora.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-book w-5 text-lg"></i>
                            <span class="ml-3 font-medium">Bitácora TI</span>
                            @if (request()->routeIs('bitacora.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                            @endif
                        </a>
                    @endrole

                    @role('admin_ti')
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-users w-5 text-lg"></i>
                            <span class="ml-3 font-medium">Gestión de Usuarios</span>
                            @if (request()->routeIs('admin.users.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                            @endif
                        </a>
                    @endrole
                </div>

                {{-- Sección Control --}}
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide">Control</p>
                    @role('admin_ti|gerencia')
                        <a href="{{ route('control.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('control.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-shield w-5 text-lg"></i>
                            <span class="ml-3 font-medium">Control TI</span>
                            @if (request()->routeIs('control.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full"></span>
                            @endif
                        </a>
                    </div>
                @endrole
            </nav>

            {{-- Usuario en Pie de Sidebar --}}
            <div class="px-4 py-6 border-t border-blue-900/30">
                <div class="flex items-center px-4 py-3 bg-blue-900/20 rounded-lg">
                    <div class="w-10 h-10 bg-[#C5A049] rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Usuario' }}</p>
                        <p class="text-xs text-blue-300">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-2 text-sm text-blue-100 hover:bg-red-900/30 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt w-4"></i>
                        <span class="ml-2">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenedor Principal --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar --}}
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="text-sm text-gray-500" x-data="{ time: '' }" x-init="time = new Date().toLocaleString('es-ES', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    setInterval(() => { time = new Date().toLocaleString('es-ES', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                        <span x-text="time"></span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-red-600 transition-colors">
                            <i class="fas fa-sign-out-alt w-4"></i>
                            <span class="ml-2">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Encabezado Condicional --}}
            @isset($header)
                <div class="px-6 py-6">
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <span class="inline-block w-1 h-8 bg-[#C5A049] rounded-full mr-4"></span>
                            {{ $header }}
                        </h1>
                    </div>
                </div>
            @endisset

            {{-- Contenido Principal --}}
            <main class="flex-1 overflow-auto px-6 py-6">
                <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-200 min-h-full">
                    <div class="prose prose-slate max-w-none">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            {{-- Pie de Página --}}
            <footer class="bg-white border-t px-6 py-4">
                <div class="text-sm text-gray-500 text-center">
                    &copy; {{ date('Y') }} {{ config('CCISUR-TI') }}. Todos los derechos reservados.
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>
