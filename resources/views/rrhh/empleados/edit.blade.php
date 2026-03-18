<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <nav class="text-sm text-slate-500 flex flex-wrap items-center gap-2 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-700">Inicio</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('empleados.index') }}" class="hover:text-slate-700">Empleados</a>
            <span class="text-slate-300">/</span>
            <span class="font-semibold text-slate-700">Editar empleado</span>
        </nav>

        <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 mb-8 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Editar empleado</h2>
                    <p class="text-sm text-slate-200">Modifica la información del empleado y guarda los cambios.</p>
                </div>

                
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 mb-6 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-xl bg-white shadow-sm overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 border-b border-sky-200 bg-sky-50">
                <h3 class="text-lg font-semibold text-sky-700">Detalles del empleado</h3>
                <span class="text-sm text-sky-600">Actualiza los datos necesarios</span>
            </header>

            <div class="p-6">
                <form method="POST" action="{{ route('empleados.update', $empleado->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nombre_completo" class="text-sm font-medium text-slate-700">Nombre completo</label>
                            <input id="nombre_completo" name="nombre_completo" type="text" value="{{ $empleado->nombre_completo }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('nombre_completo') border-red-500 @enderror">
                            @error('nombre_completo')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="correo" class="text-sm font-medium text-slate-700">Correo</label>
                            <input id="correo" name="correo" type="email" value="{{ $empleado->correo }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('correo') border-red-500 @enderror">
                            @error('correo')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="telefono" class="text-sm font-medium text-slate-700">Teléfono</label>
                            <input id="telefono" name="telefono" type="text" value="{{ $empleado->telefono }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="cargo" class="text-sm font-medium text-slate-700">Cargo</label>
                            <input id="cargo" name="cargo" type="text" value="{{ $empleado->cargo }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('cargo') border-red-500 @enderror">
                            @error('cargo')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="area" class="text-sm font-medium text-slate-700">Área</label>
                            <input id="area" name="area" type="text" value="{{ $empleado->area }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('area') border-red-500 @enderror">
                            @error('area')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="fecha_contratacion" class="text-sm font-medium text-slate-700">Fecha contratación</label>
                            <input id="fecha_contratacion" name="fecha_contratacion" type="date"
                                value="{{ $empleado->fecha_contratacion?->format('Y-m-d') }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('fecha_contratacion') border-red-500 @enderror">
                            @error('fecha_contratacion')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="vacaciones_acumuladas" class="text-sm font-medium text-slate-700">Vacaciones acumuladas (si aplica)</label>
                            <input id="vacaciones_acumuladas" name="vacaciones_acumuladas" type="number" step="0.01"
                                value="{{ $empleado->vacaciones_acumuladas }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('vacaciones_acumuladas') border-red-500 @enderror">
                            <p class="text-xs text-slate-500">Ingrese días acumulados antes de iniciar el sistema</p>
                            @error('vacaciones_acumuladas')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                        <a href="{{ route('empleados.index') }}" class="w-full sm:w-auto text-center px-4 py-2 rounded-md border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-md bg-gradient-to-r from-amber-400 to-amber-500 text-slate-900 font-semibold shadow hover:from-amber-300 hover:to-amber-400 transition">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-app-layout>
