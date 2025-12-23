<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            width: 100%;
        }

        .sidebar-mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sidebar-mobile-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .mobile-menu-container {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-menu-container.active {
            transform: translateX(0);
        }

        .sidebar-desktop {
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .main-container {
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar-desktop {
                display: none;
            }

            .main-container {
                margin-left: 0;
            }
        }

        @media (min-width: 1024px) {
            .sidebar-desktop {
                display: flex;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-b from-slate-50 via-white to-slate-100 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <!-- Overlay para menú móvil -->
        <div class="sidebar-mobile-overlay" :class="{ 'active': mobileMenuOpen }" @click="mobileMenuOpen = false"></div>

        <!-- Sidebar Desktop -->
        <aside
            class="sidebar-desktop lg:w-64 bg-gradient-to-b from-[#0C1C3C] to-[#1A2A4F] shadow-2xl flex flex-col overflow-y-auto"
            :class="{ 'lg:w-20': !sidebarOpen, 'lg:w-64': sidebarOpen }">

            <!-- Logo/Branding -->
            <div class="px-6 py-4 border-b border-blue-900/30 bg-gradient-to-r from-blue-900/40 to-transparent flex-shrink-0"
                :class="{ 'px-3': !sidebarOpen }">
                <div class="flex flex-col items-center justify-center space-y-1">
                    <div class="p-3 bg-[#C5A049]/20 rounded-lg">
                        <img src="{{ asset('storage/logos/logo2.png') }}" alt="CCISUR logo" class="h-14 w-auto">
                    </div>
                    <div class="text-center" :class="{ 'hidden': !sidebarOpen }">
                        <h1 class="text-white font-bold text-lg leading-tight">CCISUR-TI</h1>
                        <p class="text-blue-300 text-xs leading-tight">Gestión Integrada</p>
                    </div>
                </div>
            </div>

            <!-- Menú de Navegación Desktop -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <!-- Sección Principal -->
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide"
                        :class="{ 'hidden': !sidebarOpen }">Principal</p>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                        :class="{ 'justify-center px-3': !sidebarOpen }" :title="!sidebarOpen ? 'Inicio' : ''">
                        <i class="fas fa-home w-5 text-lg flex-shrink-0"></i>
                        <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Inicio</span>
                        @if (request()->routeIs('dashboard'))
                            <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                :class="{ 'hidden': !sidebarOpen }"></span>
                        @endif
                    </a>

                    @role('admin_ti|gerencia|usuario|calendario')
                        @if (Route::has('calendario-editorial.dashboard'))
                            <a href="{{ route('calendario-editorial.dashboard') }}"
                                class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('calendario-editorial.dashboard') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                                :class="{ 'justify-center px-3': !sidebarOpen }"
                                :title="!sidebarOpen ? 'Resumen Calendario' : ''">
                                <i class="fas fa-chart-line w-5 text-lg flex-shrink-0"></i>
                                <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Resumen
                                    Calendario</span>
                                @if (request()->routeIs('calendario-editorial.dashboard'))
                                    <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                        :class="{ 'hidden': !sidebarOpen }"></span>
                                @endif
                            </a>
                        @endif
                    @endrole

                    @role('admin_ti|gerencia')
                        <a href="{{ route('dashboard.ti') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.ti') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                            :class="{ 'justify-center px-3': !sidebarOpen }" :title="!sidebarOpen ? 'Dashboard TI' : ''">
                            <i class="fas fa-chart-line w-5 text-lg flex-shrink-0"></i>
                            <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Dashboard TI</span>
                            @if (request()->routeIs('dashboard.ti'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                    :class="{ 'hidden': !sidebarOpen }"></span>
                            @endif
                        </a>
                    @endrole
                </div>

                <!-- Sección Gestión -->
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide"
                        :class="{ 'hidden': !sidebarOpen }">Gestión</p>



                    <a href="{{ route('calendario-editorial.index') }}"
                        class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('calendario-editorial.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                        :class="{ 'justify-center px-3': !sidebarOpen }" :title="!sidebarOpen ? 'Calendario' : ''">
                        <i class="fas fa-calendar-alt w-5 text-lg flex-shrink-0"></i>
                        <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Calendario</span>
                        @if (request()->routeIs('calendario-editorial.*'))
                            <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                :class="{ 'hidden': !sidebarOpen }"></span>
                        @endif
                    </a>

                    @role('GOR|admin_ti|gerencia')

                        <!-- Gerencia de Operaciones Registrales -->
                        <div class="mb-2">
                            <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide"
                                :class="{ 'hidden': !sidebarOpen }">GOR</p>

                            <a href="{{ route('gor.antecedentes.index') }}"
                                class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('gor.antecedentes.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                                :class="{ 'justify-center px-3': !sidebarOpen }"
                                :title="!sidebarOpen ? 'Antecedentes Registrales' : ''">
                                <i class="fas fa-file-alt w-5 text-lg flex-shrink-0"></i>
                                <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Antecedentes
                                    Registrales</span>
                                @if (request()->routeIs('gor.antecedentes.*'))
                                    <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                        :class="{ 'hidden': !sidebarOpen }"></span>
                                @endif
                            </a>
                        </div>

                        <!-- Control de Audiencias -->
                        <a href="{{ route('audiencias.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200
       {{ request()->routeIs('audiencias.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                            :class="{ 'justify-center px-3': !sidebarOpen }"
                            :title="!sidebarOpen ? 'Control de Audiencias' : ''">

                            <i class="fas fa-gavel w-5 text-lg flex-shrink-0"></i>

                            <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">
                                Control de Audiencias
                            </span>

                            @if (request()->routeIs('audiencias.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                    :class="{ 'hidden': !sidebarOpen }"></span>
                            @endif
                        </a>

                    @endrole



                </div>

                <!-- Sección Control -->
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide"
                        :class="{ 'hidden': !sidebarOpen }">Control IT</p>
                    @role('admin_ti|gerencia')
                        <a href="{{ route('control.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('control.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                            :class="{ 'justify-center px-3': !sidebarOpen }" :title="!sidebarOpen ? 'Control TI' : ''">
                            <i class="fas fa-shield w-5 text-lg flex-shrink-0"></i>
                            <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Renovaciones</span>
                            @if (request()->routeIs('control.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                    :class="{ 'hidden': !sidebarOpen }"></span>
                            @endif
                        </a>
                    @endrole
                    @role('admin_ti|gerencia')
                        <a href="{{ route('bitacora.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('bitacora.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                            :class="{ 'justify-center px-3': !sidebarOpen }" :title="!sidebarOpen ? 'Bitácora TI' : ''">
                            <i class="fas fa-book w-5 text-lg flex-shrink-0"></i>
                            <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Bitácora TI</span>
                            @if (request()->routeIs('bitacora.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                    :class="{ 'hidden': !sidebarOpen }"></span>
                            @endif
                        </a>
                    @endrole

                    @role('admin_ti')
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                            :class="{ 'justify-center px-3': !sidebarOpen }"
                            :title="!sidebarOpen ? 'Gestión de Usuarios' : ''">
                            <i class="fas fa-users w-5 text-lg flex-shrink-0"></i>
                            <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Gestión de
                                Usuarios</span>
                            @if (request()->routeIs('admin.users.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                    :class="{ 'hidden': !sidebarOpen }"></span>
                            @endif
                        </a>
                    @endrole
                    @role('admin_ti|gerencia')
                        <a href="{{ route('plan-trabajo.index') }}"
                            class="flex items-center px-4 py-3 text-blue-100 rounded-lg transition-all duration-200 {{ request()->routeIs('plan-trabajo.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}"
                            :class="{ 'justify-center px-3': !sidebarOpen }"
                            :title="!sidebarOpen ? 'Plan de Trabajo TI' : ''">
                            <i class="fas fa-list-check w-5 text-lg flex-shrink-0"></i>
                            <span class="ml-3 font-medium truncate" :class="{ 'hidden': !sidebarOpen }">Plan de Trabajo
                                TI</span>
                            @if (request()->routeIs('plan-trabajo.*'))
                                <span class="ml-auto w-1 h-6 bg-white rounded-full flex-shrink-0"
                                    :class="{ 'hidden': !sidebarOpen }"></span>
                            @endif
                        </a>
                    @endrole
                </div>
            </nav>

            <!-- Usuario en Pie de Sidebar -->
            <div class="px-4 py-6 border-t border-blue-900/30 flex-shrink-0" :class="{ 'px-3': !sidebarOpen }">
                <div class="flex items-center px-4 py-3 bg-blue-900/20 rounded-lg"
                    :class="{ 'justify-center px-2': !sidebarOpen }">
                    <div class="w-10 h-10 bg-[#C5A049] rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0" :class="{ 'hidden': !sidebarOpen }">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Usuario' }}</p>
                        <p class="text-xs text-blue-300 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-2 text-sm text-blue-100 hover:bg-red-900/30 rounded-lg transition-all duration-200"
                        :class="{ 'justify-center': !sidebarOpen }" :title="!sidebarOpen ? 'Cerrar Sesión' : ''">
                        <i class="fas fa-sign-out-alt w-4"></i>
                        <span class="ml-2 hidden sm:inline" :class="{ 'hidden': !sidebarOpen }">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Sidebar para móvil -->
        <aside
            class="lg:hidden fixed top-0 left-0 h-full w-80 bg-gradient-to-b from-[#0C1C3C] to-[#1A2A4F] shadow-2xl z-50 overflow-y-auto mobile-menu-container"
            :class="{ 'active': mobileMenuOpen }">

            <!-- Header móvil -->
            <div class="sticky top-0 bg-gradient-to-b from-[#0C1C3C] to-[#1A2A4F] z-10 flex-shrink-0">
                <div class="flex items-center justify-between px-6 py-4 border-b border-blue-900/30">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="p-2 bg-[#C5A049]/20 rounded-lg flex-shrink-0">
                            <img src="{{ asset('storage/logos/logo2.png') }}" alt="CCISUR logo" class="h-10 w-auto">
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-white font-bold text-lg leading-tight truncate">CCISUR-TI</h1>
                            <p class="text-blue-300 text-xs leading-tight">Gestión Integrada</p>
                        </div>
                    </div>
                    <button @click="mobileMenuOpen = false"
                        class="text-white hover:text-[#C5A049] transition-colors flex-shrink-0">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Usuario en móvil -->
                <div class="px-6 py-4 border-b border-blue-900/30">
                    <div class="flex items-center min-w-0">
                        <div
                            class="w-12 h-12 bg-[#C5A049] rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Usuario' }}
                            </p>
                            <p class="text-xs text-blue-300 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menú de Navegación Móvil -->
            <nav class="px-4 py-6 space-y-1">
                <!-- Sección Principal -->
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide">Principal</p>

                    <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false"
                        class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                        <i class="fas fa-home w-6 text-xl flex-shrink-0"></i>
                        <span class="ml-4 font-medium">Inicio</span>
                        @if (request()->routeIs('dashboard'))
                            <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                        @endif
                    </a>

                    @role('admin_ti|gerencia|usuario|calendario')
                        @if (Route::has('calendario-editorial.dashboard'))
                            <a href="{{ route('calendario-editorial.dashboard') }}" @click="mobileMenuOpen = false"
                                class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('calendario-editorial.dashboard') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                                <i class="fas fa-chart-line w-6 text-xl flex-shrink-0"></i>
                                <span class="ml-4 font-medium">Resumen Calendario</span>
                                @if (request()->routeIs('calendario-editorial.dashboard'))
                                    <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                                @endif
                            </a>
                        @endif
                    @endrole

                    @role('admin_ti|gerencia')
                        <a href="{{ route('dashboard.ti') }}" @click="mobileMenuOpen = false"
                            class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.ti') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-chart-line w-6 text-xl flex-shrink-0"></i>
                            <span class="ml-4 font-medium">Dashboard TI</span>
                            @if (request()->routeIs('dashboard.ti'))
                                <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                            @endif
                        </a>
                    @endrole
                </div>

                <!-- Sección Gestión -->
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide">Gestión</p>

                    @role('admin_ti|gerencia')
                        <a href="{{ route('plan-trabajo.index') }}" @click="mobileMenuOpen = false"
                            class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('plan-trabajo.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-list-check w-6 text-xl flex-shrink-0"></i>
                            <span class="ml-4 font-medium">Plan de Trabajo TI</span>
                            @if (request()->routeIs('plan-trabajo.*'))
                                <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                            @endif
                        </a>
                    @endrole

                    <a href="{{ route('calendario-editorial.index') }}" @click="mobileMenuOpen = false"
                        class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('calendario-editorial.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                        <i class="fas fa-calendar-alt w-6 text-xl flex-shrink-0"></i>
                        <span class="ml-4 font-medium">Calendario</span>
                        @if (request()->routeIs('calendario-editorial.*'))
                            <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                        @endif
                    </a>

                    @role('GOR|admin_ti|gerencia')
                        <a href="{{ route('gor.antecedentes.index') }}" @click="mobileMenuOpen = false"
                            class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('gor.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-building w-6 text-xl flex-shrink-0"></i>
                            <span class="ml-4 font-medium">Gerencia de Operaciones Registrales</span>
                            @if (request()->routeIs('gor.*'))
                                <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                            @endif
                        </a>
                    @endrole

                    @role('admin_ti|gerencia')
                        <a href="{{ route('bitacora.index') }}" @click="mobileMenuOpen = false"
                            class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('bitacora.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-book w-6 text-xl flex-shrink-0"></i>
                            <span class="ml-4 font-medium">Bitácora TI</span>
                            @if (request()->routeIs('bitacora.*'))
                                <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                            @endif
                        </a>
                    @endrole


                </div>

                <!-- Sección Control -->
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wide">Control</p>
                    @role('admin_ti|gerencia')
                        <a href="{{ route('control.index') }}" @click="mobileMenuOpen = false"
                            class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('control.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-shield w-6 text-xl flex-shrink-0"></i>
                            <span class="ml-4 font-medium">Control TI</span>
                            @if (request()->routeIs('control.*'))
                                <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                            @endif
                        </a>
                    @endrole

                    @role('admin_ti')
                        <a href="{{ route('admin.users.index') }}" @click="mobileMenuOpen = false"
                            class="flex items-center px-4 py-4 text-blue-100 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#C5A049] text-white shadow-lg' : 'hover:bg-blue-900/30' }}">
                            <i class="fas fa-users w-6 text-xl flex-shrink-0"></i>
                            <span class="ml-4 font-medium">Gestión de Usuarios</span>
                            @if (request()->routeIs('admin.users.*'))
                                <span class="ml-auto w-2 h-8 bg-white rounded-full flex-shrink-0"></span>
                            @endif
                        </a>
                    @endrole
                </div>

                <!-- Cerrar sesión en móvil -->
                <div class="mt-8 px-4 border-t border-blue-900/30 pt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" @click="mobileMenuOpen = false"
                            class="w-full flex items-center justify-center px-6 py-4 text-sm text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all duration-200 shadow-lg">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3 font-medium">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Contenedor Principal -->
        <div class="flex-1 flex flex-col overflow-hidden main-container" :class="{ 'lg:ml-20': !sidebarOpen }">

            <!-- Topbar -->
            <header class="bg-white shadow-sm border-b flex-shrink-0">
                <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 gap-2 sm:gap-4">
                    <!-- Botón menú móvil -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden text-gray-600 hover:text-gray-900 transition-colors flex-shrink-0">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Botón menú desktop -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="hidden lg:block text-gray-600 hover:text-gray-900 transition-colors flex-shrink-0">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Título en móvil -->
                    <div class="lg:hidden flex items-center space-x-2 min-w-0">
                        <div class="w-8 h-8 bg-[#C5A049] rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-laptop-code text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-800 truncate text-sm">CCISUR-TI</span>
                    </div>

                    <!-- Reloj y hora -->
                    <div class="text-sm text-gray-500 flex-shrink-0" x-data="{ time: '' }" x-init="const updateTime = () => {
                        time = new Date().toLocaleString('es-ES', {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                    };
                    updateTime();
                    setInterval(updateTime, 1000);">
                        <div class="hidden md:flex items-center space-x-2">
                            <i class="fas fa-clock text-[#C5A049]"></i>
                            <span x-text="time" class="font-medium"></span>
                        </div>
                        <div class="md:hidden">
                            <span x-text="time.split(',')[1]?.trim() || ''" class="font-medium text-xs"></span>
                        </div>
                    </div>

                    <!-- Botón cerrar sesión desktop -->
                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:block flex-shrink-0">
                        @csrf
                        <button type="submit"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-red-600 transition-colors whitespace-nowrap">
                            <i class="fas fa-sign-out-alt w-4"></i>
                            <span class="ml-2">Cerrar Sesión</span>
                        </button>
                    </form>

                    <!-- Botón cerrar sesión móvil (ícono pequeño) -->
                    <button @click="document.querySelector('#logout-form-mobile').submit()"
                        class="lg:hidden text-gray-600 hover:text-red-600 transition-colors flex-shrink-0">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                    </button>
                    <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>
                </div>
            </header>

            <!-- Encabezado Condicional -->
            @isset($header)
                <div class="px-4 sm:px-6 py-3 sm:py-6 flex-shrink-0">
                    <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6 border border-gray-200">
                        <h1
                            class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 flex items-center gap-3 sm:gap-4">
                            <span
                                class="inline-block w-1 h-6 sm:h-7 lg:h-8 bg-[#C5A049] rounded-full flex-shrink-0"></span>
                            <span class="truncate">{{ $header }}</span>
                        </h1>
                    </div>
                </div>
            @endisset

            <!-- Contenido Principal -->
            <main class="flex-1 overflow-auto px-4 sm:px-6 py-4 sm:py-6">
                <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6 lg:p-8 border border-gray-200 min-h-full">
                    <div class="prose prose-sm sm:prose prose-slate max-w-none">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            <!-- Pie de Página -->
            <footer class="bg-white border-t px-4 sm:px-6 py-3 sm:py-4 text-center flex-shrink-0">
                <div class="text-xs sm:text-sm text-gray-500">
                    &copy; {{ date('Y') }} CCISUR-TI. Todos los derechos reservados.
                    <span class="hidden sm:inline">| Sistema de Gestión Tecnológica</span>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Detectar clics fuera del menú móvil
            document.addEventListener('click', function(event) {
                const mobileMenu = document.querySelector('.mobile-menu-container');
                const overlay = document.querySelector('.sidebar-mobile-overlay');

                if (mobileMenu && mobileMenu.classList.contains('active')) {
                    if (!mobileMenu.contains(event.target) && !event.target.closest(
                            '[\\@click*="mobileMenuOpen"]')) {
                        mobileMenu.classList.remove('active');
                    }
                }
            });

            // Cerrar menú móvil al presionar ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const mobileMenu = document.querySelector('.mobile-menu-container');
                    if (mobileMenu) {
                        mobileMenu.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>

</html>
