<?php

use App\Services\Rrhh\VacacionesService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('rrhh:acreditar-vacaciones', function (VacacionesService $vacaciones) {
    $resultado = $vacaciones->sincronizarTodos();

    $this->info("Acreditaciones nuevas: {$resultado['acreditaciones']} (empleados: {$resultado['empleados']}).");
})->purpose('Acredita las vacaciones por aniversario laboral y recalcula saldos (idempotente)');

Schedule::command('rrhh:acreditar-vacaciones')->dailyAt('00:30');

Artisan::command('archivos:privatizar', function () {
    $publico = Storage::disk('public');
    $privado = Storage::disk('local');
    $movidos = 0;

    // Misma ruta relativa en ambos discos: los registros en BD no cambian
    foreach (['bitacora_evidencias', 'evidencias', 'calendario_editorial', 'gor/comprobantes'] as $carpeta) {
        foreach ($publico->allFiles($carpeta) as $ruta) {
            if ($privado->exists($ruta) || $privado->put($ruta, $publico->get($ruta))) {
                $publico->delete($ruta);
                $movidos++;
            } else {
                $this->error("No se pudo copiar {$ruta}; se deja en el disco público.");
            }
        }
    }

    $this->info("Archivos movidos al disco privado: {$movidos}.");
})->purpose('Mueve evidencias, adjuntos y comprobantes del disco público al privado (idempotente)');
