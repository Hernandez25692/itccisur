<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Error | CCISUR</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex items-center justify-center bg-[#0C1C3C] text-white">
    <div class="text-center space-y-4">
        <h1 class="text-4xl font-black">Error</h1>
        <p class="text-white/80">No se pudo completar la solicitud.</p>
        <a href="{{ url('/') }}"
            class="inline-block mt-4 px-6 py-3 rounded-xl bg-[#C5A049] text-[#0C1C3C] font-semibold">
            Volver al inicio
        </a>
    </div>
</body>

</html>
