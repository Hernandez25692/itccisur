<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6 md:py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <!-- Encabezado de navegación -->
            <div class="mb-6 md:mb-8">
                <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                    <a href="{{ route('audiencias.index') }}"
                        class="hover:text-gray-900 transition duration-200 flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Volver</span>
                    </a>
                    <span>/</span>
                    <a href="{{ route('audiencias.index') }}" class="hover:text-gray-900 transition duration-200">
                        Audiencias
                    </a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Detalle</span>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                                Detalle de Audiencia
                            </h1>
                            <p class="text-gray-600 mt-1">
                                Información completa del registro
                            </p>
                        </div>
                    </div>

                    <!-- Badge de estado -->
                    <div class="flex items-center gap-3">
                        @if ($audiencia->fecha_hora_atencion)
                            <span
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Atendido
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                Pendiente
                            </span>
                        @endif

                        <span class="text-sm text-gray-500">
                            ID: #{{ str_pad($audiencia->id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta principal de detalle -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
                <!-- Encabezado de la tarjeta -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Información General
                        </h2>
                        <div class="text-sm text-gray-500">
                            Registrado el {{ $audiencia->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Columna izquierda: Información principal -->
                        <div class="space-y-6">
                            <!-- Información del solicitante -->
                            <div class="space-y-4">
                                <h3
                                    class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Información del Solicitante
                                </h3>

                                <div class="bg-gray-50 rounded-xl p-5 space-y-4">
                                    <div>
                                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Nombre
                                        </div>
                                        <p class="text-lg font-medium text-gray-900">
                                            {{ $audiencia->nombre_solicitante }}</p>
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            N° Documento
                                        </div>
                                        <p
                                            class="text-lg font-mono font-medium text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg inline-block">
                                            {{ $audiencia->numero_documento }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fechas importantes -->
                            <div class="space-y-4">
                                <h3
                                    class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Fechas
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-blue-50 rounded-xl p-4">
                                        <div class="flex items-center gap-2 text-sm text-blue-600 mb-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Recepción
                                        </div>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ $audiencia->fecha_recepcion->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $audiencia->fecha_recepcion->isoFormat('dddd') }}
                                        </p>
                                    </div>

                                    @if ($audiencia->fecha_hora_atencion)
                                        <div class="bg-green-50 rounded-xl p-4">
                                            <div class="flex items-center gap-2 text-sm text-green-600 mb-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Atención
                                            </div>
                                            <p class="text-lg font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($audiencia->fecha_hora_atencion)->format('d/m/Y') }}
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($audiencia->fecha_hora_atencion)->format('H:i') }}
                                                horas
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha: Detalles adicionales -->
                        <div class="space-y-6">
                            <!-- Motivo -->
                            <div class="space-y-4">
                                <h3
                                    class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Motivo
                                </h3>

                                <div class="bg-gray-50 rounded-xl p-5">
                                    <div class="prose prose-sm max-w-none">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $audiencia->motivo }}</p>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-200 text-xs text-gray-500">
                                        {{ str_word_count($audiencia->motivo) }} palabras
                                    </div>
                                </div>
                            </div>

                            <!-- Dictamen (si existe) -->
                            @if ($audiencia->dictamen)
                                <div class="space-y-4">
                                    <h3
                                        class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Dictamen
                                    </h3>

                                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                                        <div class="prose prose-sm max-w-none">
                                            <p class="text-gray-700 whitespace-pre-line">{{ $audiencia->dictamen }}
                                            </p>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-blue-200 text-xs text-blue-600">
                                            Dictamen registrado
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de registro -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Registrado por</p>
                                    <p class="text-gray-900">{{ $audiencia->creador->name ?? 'N/D' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-700">Última actualización</p>
                                    <p class="text-gray-900">{{ $audiencia->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de acciones -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <p>¿Necesitas realizar cambios en esta audiencia?</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('audiencias.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-200 shadow-sm hover:shadow text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver al listado
                        </a>

                        <a href="{{ route('audiencias.edit', $audiencia->id) }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar Audiencia
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información de tiempo transcurrido -->
                <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tiempo transcurrido
                    </h3>

                    <div class="space-y-2">
                        @php
                            $now = \Carbon\Carbon::now();
                            $recepcion = $audiencia->fecha_recepcion;
                            $diasDesdeRecepcion = (int) $recepcion->diffInDays($now);

                            if ($audiencia->fecha_hora_atencion) {
                                $atencion = \Carbon\Carbon::parse($audiencia->fecha_hora_atencion);
                                $diasParaAtencion = (int) $recepcion->diffInDays($atencion);
                            }
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Desde recepción:</span>
                            <span class="font-semibold text-gray-900">{{ $diasDesdeRecepcion }} días</span>
                        </div>

                        @if ($audiencia->fecha_hora_atencion)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Tiempo hasta atención:</span>
                                <span class="font-semibold text-green-600">{{ $diasParaAtencion }} días</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl border border-blue-200 p-5">
                    <h3 class="text-sm font-semibold text-blue-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Acciones rápidas
                    </h3>

                    <div class="flex flex-col sm:flex-row gap-3">
                        @if (!$audiencia->fecha_hora_atencion)
                            <a href="{{ route('audiencias.edit', $audiencia->id) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-medium rounded-lg border border-blue-200 hover:bg-blue-50 transition duration-200 shadow-sm flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Marcar como atendido
                            </a>
                        @endif

                        <button onclick="printFullDetails()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-amber-700 text-sm font-medium rounded-lg border border-amber-300 hover:bg-amber-50 transition duration-200 shadow-sm flex-1 sm:flex-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimir
                        </button>

                        
                    </div>
                </div>

                <div id="printContent" class="hidden">
                    <div class="print-document bg-white p-8">
                        <div class="mb-8 border-b-2 border-gray-900 pb-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalle de Audiencia</h1>
                            <p class="text-gray-600">ID: #{{ str_pad($audiencia->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-gray-500 mt-2">Generado el {{ now()->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Solicitante</h2>
                                <p class="mb-2"><span class="font-semibold">Nombre:</span> {{ $audiencia->nombre_solicitante }}</p>
                                <p><span class="font-semibold">Documento:</span> {{ $audiencia->numero_documento }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Estado</h2>
                                <p class="mb-2"><span class="font-semibold">Estado:</span> 
                                    @if ($audiencia->fecha_hora_atencion) Atendido @else Pendiente @endif
                                </p>
                                <p><span class="font-semibold">Fecha Recepción:</span> {{ $audiencia->fecha_recepcion->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-sm font-bold text-gray-900 uppercase mb-3 pb-2 border-b border-gray-300">Motivo</h2>
                            <p class="text-gray-700 whitespace-pre-line text-justify">{{ $audiencia->motivo }}</p>
                        </div>

                        @if ($audiencia->fecha_hora_atencion)
                            <div class="mb-8">
                                <h2 class="text-sm font-bold text-gray-900 uppercase mb-3 pb-2 border-b border-gray-300">Atención</h2>
                                <p class="text-gray-700 mb-2"><span class="font-semibold">Fecha/Hora:</span> 
                                    {{ \Carbon\Carbon::parse($audiencia->fecha_hora_atencion)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif

                        @if ($audiencia->dictamen)
                            <div class="mb-8">
                                <h2 class="text-sm font-bold text-gray-900 uppercase mb-3 pb-2 border-b border-gray-300">Dictamen</h2>
                                <p class="text-gray-700 whitespace-pre-line text-justify">{{ $audiencia->dictamen }}</p>
                            </div>
                        @endif

                        <div class="mt-12 pt-6 border-t border-gray-300 text-xs text-gray-600">
                            <p><span class="font-semibold">Registrado por:</span> {{ $audiencia->creador->name ?? 'N/D' }}</p>
                            <p><span class="font-semibold">Última actualización:</span> {{ $audiencia->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

            <script>
            function printFullDetails() {
                const printWindow = window.open('', '_blank');
                const printContent = document.getElementById('printContent').innerHTML;
                
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Audiencia #{{ str_pad($audiencia->id, 6, '0', STR_PAD_LEFT) }}</title>
                        <style>
                            * {
                                margin: 0;
                                padding: 0;
                                box-sizing: border-box;
                            }
                            
                            body {
                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                background: white;
                                color: #333;
                                line-height: 1.5;
                            }
                            
                            .print-document {
                                max-width: 210mm;
                                height: 297mm;
                                margin: 0 auto;
                                padding: 20mm;
                                background: white;
                                overflow: hidden;
                            }
                            
                            .print-header {
                                border-bottom: 3px solid #1e40af;
                                margin-bottom: 20px;
                                padding-bottom: 15px;
                                text-align: center;
                            }
                            
                            .print-header h1 {
                                font-size: 24px;
                                color: #1e40af;
                                margin-bottom: 5px;
                            }
                            
                            .print-header p {
                                font-size: 12px;
                                color: #666;
                            }
                            
                            .grid-cols-2 {
                                display: grid;
                                grid-template-columns: 1fr 1fr;
                                gap: 20px;
                                margin-bottom: 15px;
                            }
                            
                            .section-title {
                                font-size: 11px;
                                font-weight: 700;
                                color: #1e40af;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                                margin-top: 12px;
                                margin-bottom: 8px;
                                padding-bottom: 5px;
                                border-bottom: 1px solid #ddd;
                            }
                            
                            .field {
                                margin-bottom: 8px;
                                font-size: 12px;
                            }
                            
                            .field-label {
                                font-weight: 600;
                                color: #555;
                                display: inline;
                            }
                            
                            .field-value {
                                color: #333;
                            }
                            
                            .status-badge {
                                display: inline-block;
                                padding: 4px 10px;
                                border-radius: 4px;
                                font-weight: 600;
                                font-size: 11px;
                            }
                            
                            .status-pending {
                                background-color: #fef08a;
                                color: #854d0e;
                            }
                            
                            .status-attended {
                                background-color: #bbf7d0;
                                color: #166534;
                            }
                            
                            .content-box {
                                background-color: #f9fafb;
                                padding: 10px;
                                border-left: 3px solid #1e40af;
                                margin: 10px 0;
                                font-size: 12px;
                                line-height: 1.5;
                                white-space: pre-wrap;
                            }
                            
                            .print-footer {
                                margin-top: 15px;
                                padding-top: 10px;
                                border-top: 1px solid #ddd;
                                font-size: 10px;
                                color: #999;
                            }
                            
                            .footer-item {
                                margin-bottom: 3px;
                            }
                            
                            @media print {
                                body {
                                    margin: 0;
                                    padding: 0;
                                    background: white;
                                }
                                .print-document {
                                    max-width: 100%;
                                    height: auto;
                                    padding: 0;
                                    margin: 0;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="print-document">
                            <div class="print-header">
                                <h1>📋 Detalle de Audiencia</h1>
                                <p>ID: #{{ str_pad($audiencia->id, 6, '0', STR_PAD_LEFT) }} | Generado: {{ now()->format('d/m/Y H:i') }}</p>
                                <p>
                                    <span class="status-badge 
                                        @if($audiencia->fecha_hora_atencion) status-attended @else status-pending @endif">
                                        @if($audiencia->fecha_hora_atencion) ✓ Atendido @else ⏳ Pendiente @endif
                                    </span>
                                </p>
                            </div>

                            <div class="grid-cols-2">
                                <div>
                                    <h2 class="section-title">👤 Solicitante</h2>
                                    <div class="field">
                                        <span class="field-label">Nombre:</span>
                                        <span class="field-value">{{ $audiencia->nombre_solicitante }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="field-label">Documento:</span>
                                        <span class="field-value">{{ $audiencia->numero_documento }}</span>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="section-title">📅 Fechas</h2>
                                    <div class="field">
                                        <span class="field-label">Recepción:</span>
                                        <span class="field-value">{{ $audiencia->fecha_recepcion->format('d/m/Y') }}</span>
                                    </div>
                                    @if($audiencia->fecha_hora_atencion)
                                    <div class="field">
                                        <span class="field-label">Atención:</span>
                                        <span class="field-value">{{ \Carbon\Carbon::parse($audiencia->fecha_hora_atencion)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h2 class="section-title">💬 Motivo</h2>
                                <div class="content-box">{{ $audiencia->motivo }}</div>
                            </div>

                            @if($audiencia->dictamen)
                            <div>
                                <h2 class="section-title">✅ Dictamen</h2>
                                <div class="content-box">{{ $audiencia->dictamen }}</div>
                            </div>
                            @endif

                            <div class="print-footer">
                                <div class="footer-item"><span class="field-label">Registrado por:</span> {{ $audiencia->creador->name ?? 'N/D' }}</div>
                                <div class="footer-item"><span class="field-label">Actualizado:</span> {{ $audiencia->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
                printWindow.print();
            }

            function downloadPDF() {
                alert('Para descargar como PDF, usa la opción de imprimir y selecciona "Guardar como PDF"');
                printFullDetails();
            }
            </script>
            </div>

        </div>
    </div>

    <!-- Script para funcionalidades adicionales -->
    <script>
        // Función para copiar información al portapapeles
        function copyToClipboard(text, elementId) {
            navigator.clipboard.writeText(text).then(() => {
                const element = document.getElementById(elementId);
                const originalText = element.textContent;

                element.textContent = '¡Copiado!';
                element.classList.add('text-green-600');

                setTimeout(() => {
                    element.textContent = originalText;
                    element.classList.remove('text-green-600');
                }, 2000);
            });
        }

        // Preparar datos para copiar
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar funcionalidad de copiar al documento
            const docNumber = "{{ $audiencia->numero_documento }}";
            const docElement = document.querySelector('.font-mono');

            if (docElement) {
                docElement.addEventListener('click', function() {
                    copyToClipboard(docNumber, 'doc-number');
                });

                // Agregar tooltip
                docElement.title = 'Click para copiar';
                docElement.classList.add('cursor-pointer', 'hover:bg-gray-200', 'transition');
            }

            // Formatear fechas para mejor presentación
            const fechaElements = document.querySelectorAll('[data-date]');
            fechaElements.forEach(el => {
                const date = new Date(el.dataset.date);
                el.textContent = date.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            });

            // Animación para los badges de estado
            const badges = document.querySelectorAll('[class*="bg-"] span[class*="bg-"]');
            badges.forEach(badge => {
                badge.style.animation = 'pulse 2s infinite';
            });
        });

        // Agregar estilos CSS para animaciones
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
            
            .prose p {
                margin-bottom: 0.5em;
                line-height: 1.6;
            }
            
            .hover-lift {
                transition: transform 0.2s ease;
            }
            
            .hover-lift:hover {
                transform: translateY(-2px);
            }
            
            @media print {
                .no-print {
                    display: none !important;
                }
                
                body {
                    background: white !important;
                }
                
                .bg-gradient-to-br, .bg-white, .bg-gray-50, .bg-blue-50, .bg-green-50 {
                    background: white !important;
                }
                
                .shadow-xl, .shadow-lg, .shadow-md, .shadow-sm {
                    box-shadow: none !important;
                }
                
                .rounded-2xl, .rounded-xl, .rounded-lg {
                    border-radius: 0 !important;
                }
                
                .border, .border-t, .border-b {
                    border-color: #000 !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Estilos personalizados para impresión -->
    <style>
        @media print {

            nav,
            .bg-gradient-to-br,
            button,
            a:not(.print-link) {
                display: none !important;
            }

            .print-container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .print-header {
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .print-section {
                page-break-inside: avoid;
                margin-bottom: 20px;
            }

            .print-footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #000;
                font-size: 12px;
                color: #666;
            }
        }

        /* Mejoras de animación */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Efectos hover para tarjetas */
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Estilos para scroll suave */
        html {
            scroll-behavior: smooth;
        }

        /* Mejoras responsivas */
        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .text-3xl {
                font-size: 1.5rem;
            }

            .p-8 {
                padding: 1.5rem;
            }
        }
    </style>
</x-app-layout>
