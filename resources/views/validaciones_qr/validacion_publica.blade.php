<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Formación - CCISUR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">

        <div class="bg-[#0B1F3A] text-white p-8 text-center">
            <h1 class="text-3xl font-bold">CCISUR</h1>
            <p class="text-sm mt-2 opacity-90">Validación pública de formación</p>
        </div>

        <div class="p-8">
            @if ($valido && $registro)
                <div class="text-center mb-8">
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="text-2xl font-bold text-green-700">Registro válido</h2>
                    <p class="text-slate-600 mt-2">
                        La información consultada corresponde a un registro válido en el sistema.
                    </p>
                </div>

                <div class="grid gap-4">
                    <div class="bg-slate-50 border rounded-2xl p-4">
                        <p class="text-sm text-slate-500">Nombre de la formación</p>
                        <p class="text-lg font-semibold text-slate-800">{{ $registro->nombre_formacion }}</p>
                    </div>

                    <div class="bg-slate-50 border rounded-2xl p-4">
                        <p class="text-sm text-slate-500">Fecha</p>
                        <p class="text-lg font-semibold text-slate-800">
                            {{ $registro->fecha_formacion ?? 'No registrada' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 border rounded-2xl p-4">
                        <p class="text-sm text-slate-500">Año</p>
                        <p class="text-lg font-semibold text-slate-800">{{ $registro->anio ?? 'No registrado' }}</p>
                    </div>

                    <div class="bg-slate-50 border rounded-2xl p-4">
                        <p class="text-sm text-slate-500">Código de validación</p>
                        <p class="text-sm font-mono text-slate-700 break-all">{{ $registro->token }}</p>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="text-6xl mb-4">❌</div>
                    <h2 class="text-2xl font-bold text-red-700">Registro no válido</h2>
                    <p class="text-slate-600 mt-3">
                        El código consultado no existe o no se encuentra activo en el sistema.
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-slate-50 border-t px-8 py-4 text-center text-sm text-slate-500">
            Cámara de Comercio e Industrias del Sur
        </div>
    </div>
</body>

</html>
