<?php

use App\Services\Rrhh\VacacionesService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('rrhh:acreditar-vacaciones', function (VacacionesService $vacaciones) {
    $resultado = $vacaciones->sincronizarTodos();

    $this->info("Acreditaciones nuevas: {$resultado['acreditaciones']} (empleados: {$resultado['empleados']}).");
})->purpose('Acredita las vacaciones por aniversario laboral y recalcula saldos (idempotente)');

Schedule::command('rrhh:acreditar-vacaciones')->dailyAt('00:30');
