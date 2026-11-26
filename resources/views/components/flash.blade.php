{{-- Mensajes de éxito/error de los controladores y errores de validación --}}
@props(['errores' => true])

@if (session('success') || session('error') || ($errores && $errors->any()))
    <div {{ $attributes->merge(['class' => 'space-y-3 mb-6']) }} role="status">
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                <span aria-hidden="true">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <span aria-hidden="true">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errores && $errors->any())
            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <span aria-hidden="true">⚠️</span>
                <ul class="text-sm list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
