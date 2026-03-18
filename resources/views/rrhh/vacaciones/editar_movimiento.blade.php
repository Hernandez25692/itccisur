<x-app-layout>
    <div class="container mx-auto px-4 py-8">
       
        <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 mb-8 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Editar movimiento</h2>
                    <p class="text-sm text-slate-200">
                        Modifica los detalles del movimiento de vacaciones.
                    </p>
                </div>

                
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 mb-6 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-xl bg-white shadow-sm overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 border-b border-amber-200 bg-amber-50">
                <h3 class="text-lg font-semibold text-amber-700">Detalles del movimiento</h3>
                <span class="text-sm text-amber-600">Actualiza la información necesaria</span>
            </header>

            <div class="p-6">
                <form method="POST" action="{{ route('vacaciones.actualizar', $movimiento->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="dias_equivalentes" class="text-sm font-medium text-slate-700">
                                Días equivalentes
                            </label>
                            <input id="dias_equivalentes" name="dias_equivalentes" type="number" step="0.25"
                                value="{{ $movimiento->dias_equivalentes }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('dias_equivalentes') border-red-500 @enderror">
                            @error('dias_equivalentes')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="horas_equivalentes" class="text-sm font-medium text-slate-700">
                                Horas equivalentes
                            </label>
                            <input id="horas_equivalentes" name="horas_equivalentes" type="number" step="0.25"
                                value="{{ $movimiento->horas_equivalentes }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('horas_equivalentes') border-red-500 @enderror">
                            @error('horas_equivalentes')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="motivo" class="text-sm font-medium text-slate-700">
                                Motivo
                            </label>
                            <textarea id="motivo" name="motivo" rows="4"
                                class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('motivo') border-red-500 @enderror">{{ $movimiento->motivo }}</textarea>
                            @error('motivo')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                        <a href="{{ url()->previous() }}" class="w-full sm:w-auto text-center px-4 py-2 rounded-md border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-md bg-gradient-to-r from-amber-400 to-amber-500 text-slate-900 font-semibold shadow hover:from-amber-300 hover:to-amber-400 transition">
                            Actualizar movimiento
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-app-layout>

