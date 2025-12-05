<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-white via-slate-50 to-gray-100 text-gray-800">
        <div class="min-h-screen">
            {{-- Navegación (mantiene tu include) --}}
            <div class="bg-white/60 backdrop-blur-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @include('layouts.navigation')
                </div>
            </div>

            {{-- Encabezado opcional --}}
            @isset($header)
                <header class="mt-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="bg-white shadow-sm rounded-lg p-6 border">
                            <h1 class="text-2xl font-semibold text-gray-900">
                                {{ $header }}
                            </h1>
                        </div>
                    </div>
                </header>
            @endisset

            {{-- Contenido principal --}}
            <main class="py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white shadow-lg rounded-xl p-6 sm:p-8 border">
                        <div class="prose prose-slate">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>

            {{-- Pie de página simple (opcional) --}}
            <footer class="mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-sm text-gray-500 py-6">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
