<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Error' }} | CCISUR</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">
    <!-- Fondo institucional -->
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0C1C3C] via-[#13264d] to-[#1A2A4F]"></div>
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-[#C5A049]/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

        <!-- Contenido -->
        <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-3xl">
                <div class="rounded-3xl bg-white/95 shadow-2xl border border-white/20 overflow-hidden">
                    <div class="p-8 sm:p-10">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#C5A049] to-[#D8B96E] flex items-center justify-center shadow">
                                    <svg class="h-6 w-6 text-[#0C1C3C]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.52 20h15a2 2 0 001.73-3.14l-7.5-13a2 2 0 00-3.46 0z" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-[#0C1C3C]/70 tracking-wide uppercase">CCISUR
                                    </p>
                                    <h1 class="text-2xl sm:text-3xl font-black text-[#0C1C3C] leading-tight">
                                        {{ $title ?? 'Ha ocurrido un error' }}
                                    </h1>
                                </div>
                            </div>

                            <div class="text-left sm:text-right">
                                <p class="text-sm text-gray-500">Código</p>
                                <p class="text-4xl font-black text-[#0C1C3C]">{{ $code ?? '—' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50 p-5">
                            <p class="text-gray-700 leading-relaxed">
                                {{ $message ?? 'No pudimos completar la acción solicitada.' }}
                            </p>

                            @isset($hint)
                                <p class="mt-3 text-sm text-gray-500">
                                    <span class="font-semibold text-gray-700">Sugerencia:</span> {{ $hint }}
                                </p>
                            @endisset
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-gray-200 px-5 py-3 font-semibold text-gray-800 hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Volver
                            </a>

                            <a href="{{ url('/') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] px-5 py-3 font-semibold text-white shadow hover:shadow-lg transition">
                                Ir al inicio
                            </a>

                            <a href="mailto:soporte@ccisur.org"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#C5A049] to-[#D8B96E] px-5 py-3 font-semibold text-[#0C1C3C] shadow hover:shadow-lg transition sm:ml-auto">
                                Reportar a soporte
                            </a>
                        </div>
                    </div>

                    <div
                        class="px-8 sm:px-10 py-4 bg-[#0C1C3C] text-white/80 text-sm flex items-center justify-between">
                        <span>Departamento de TI</span>
                        <span class="font-semibold text-white">CCISUR</span>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</body>

</html>
