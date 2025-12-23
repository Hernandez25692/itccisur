    <?php

    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\PlanTrabajoController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\PlanActividadController;
    use App\Http\Controllers\CalendarioEditorialController;
    use App\Http\Controllers\CalendarioEditorialDashboardController;
    use App\Http\Controllers\GorAntecedenteRegistralController;

    Route::get('/', function () {
        return view('welcome');
    });


    Route::middleware(['auth', 'role:admin_ti'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);
        });



    Route::middleware(['auth'])->group(function () {

        Route::get(
            'plan-trabajo/{plan}/actividad/create',
            [PlanActividadController::class, 'create']
        )
            ->name('plan-actividad.create');

        Route::post(
            'plan-trabajo/{plan}/actividad',
            [PlanActividadController::class, 'store']
        )
            ->name('plan-actividad.store');

        Route::post(
            'plan-actividad/{plan_actividad}/ejecucion',
            [\App\Http\Controllers\ActividadEjecucionController::class, 'store']
        )->name('actividad.ejecucion.store');
    });



    Route::middleware(['auth'])->group(function () {

        Route::prefix('control')->group(function () {
            Route::get('/', [\App\Http\Controllers\ControlRecordatorioController::class, 'index'])->name('control.index');
            Route::get('/crear', [\App\Http\Controllers\ControlRecordatorioController::class, 'create'])->name('control.create');
            Route::post('/', [\App\Http\Controllers\ControlRecordatorioController::class, 'store'])->name('control.store');
            Route::get('/{id}/editar', [\App\Http\Controllers\ControlRecordatorioController::class, 'edit'])->name('control.edit');
            Route::put('/{id}', [\App\Http\Controllers\ControlRecordatorioController::class, 'update'])->name('control.update');
            Route::delete('/{id}', [\App\Http\Controllers\ControlRecordatorioController::class, 'destroy'])->name('control.destroy');
        });
    });

    Route::middleware(['auth', 'role:admin_ti|gerencia|usuario|calendario'])
        ->prefix('calendario-editorial')
        ->name('calendario-editorial.')
        ->group(function () {

            Route::get('/dashboard', [CalendarioEditorialDashboardController::class, 'index'])
                ->name('dashboard');
        });

    Route::middleware(['auth', 'role:GOR|admin_ti|gerencia'])
        ->prefix('gor')
        ->name('gor.')
        ->group(function () {

            Route::get('/antecedentes', [GorAntecedenteRegistralController::class, 'index'])
                ->name('antecedentes.index');

            Route::get('/antecedentes/create', [GorAntecedenteRegistralController::class, 'create'])
                ->name('antecedentes.create');

            Route::post('/antecedentes', [GorAntecedenteRegistralController::class, 'store'])
                ->name('antecedentes.store');

            Route::get('/antecedentes/{id}/edit', [GorAntecedenteRegistralController::class, 'edit'])
                ->name('antecedentes.edit');

            Route::put('/antecedentes/{id}', [GorAntecedenteRegistralController::class, 'update'])
                ->name('antecedentes.update');

            Route::get(
                '/antecedentes/{id}',
                [GorAntecedenteRegistralController::class, 'show']
            )->name('antecedentes.show');
        });



    Route::middleware(['auth', 'role:admin_ti|calendario'])
        ->prefix('calendario-editorial')
        ->name('calendario-editorial.')
        ->group(function () {

            Route::get('/', [CalendarioEditorialController::class, 'index'])
                ->name('index');

            Route::get('/crear', [CalendarioEditorialController::class, 'create'])
                ->name('create');

            Route::post('/', [CalendarioEditorialController::class, 'store'])
                ->name('store');

            Route::get('/{calendarioEditorial}/editar', [CalendarioEditorialController::class, 'edit'])
                ->name('edit');

            Route::put('/{calendarioEditorial}', [CalendarioEditorialController::class, 'update'])
                ->name('update');

            // Acción exclusiva admin_ti
            Route::post('/{calendarioEditorial}/publicar', [CalendarioEditorialController::class, 'marcarPublicado'])
                ->middleware('role:admin_ti')
                ->name('publicar');

            Route::get(
                '/{calendarioEditorial}',
                [CalendarioEditorialController::class, 'show']
            )->name('show');

   
        });

    Route::delete(
        '/calendario-editorial/adjuntos/{adjunto}',
        [CalendarioEditorialController::class, 'destroyAdjunto']
    )->name('calendario-editorial.adjuntos.destroy');


    // Bitácora de Actividades TI
    Route::middleware(['auth'])->group(function () {

        Route::get('/bitacora', [App\Http\Controllers\BitacoraActividadController::class, 'index'])
            ->name('bitacora.index');
        Route::get('/bitacora/create', [App\Http\Controllers\BitacoraActividadController::class, 'create'])
            ->name('bitacora.create');
        Route::get('/bitacora/{id}', [App\Http\Controllers\BitacoraActividadController::class, 'show'])
            ->name('bitacora.show');
        Route::post('/bitacora', [App\Http\Controllers\BitacoraActividadController::class, 'store'])
            ->name('bitacora.store');

        Route::get('/bitacora/{id}/editar', [App\Http\Controllers\BitacoraActividadController::class, 'edit'])
            ->name('bitacora.edit');

        Route::put('/bitacora/{id}', [App\Http\Controllers\BitacoraActividadController::class, 'update'])
            ->name('bitacora.update');
    });
    Route::middleware(['auth', 'role:admin_ti|gerencia'])->group(function () {
        Route::get('/gerente/dashboard-ti', [App\Http\Controllers\DashboardTIController::class, 'index'])
            ->name('dashboard.ti');
    });

    Route::middleware(['auth'])->group(function () {

        Route::resource('plan-trabajo', PlanTrabajoController::class)
            ->parameters(['plan-trabajo' => 'plan']);


        Route::post(
            'plan-trabajo/{plan}/aprobar',
            [PlanTrabajoController::class, 'aprobar']
        )
            ->name('plan-trabajo.aprobar');

        Route::get(
            'plan-actividad/{actividad}/edit',
            [PlanActividadController::class, 'edit']
        )
            ->name('plan-actividad.edit');

        Route::put(
            'plan-actividad/{actividad}',
            [PlanActividadController::class, 'update']
        )
            ->name('plan-actividad.update');

        Route::get(
            'plan-actividad/{actividad}',
            [PlanActividadController::class, 'show']
        )
            ->name('plan-actividad.show');
    });
    // Enviar a revisión (Admin TI)
    Route::middleware(['auth', 'role:admin_ti'])->group(function () {
        Route::post('plan-trabajo/{plan}/enviar', [PlanTrabajoController::class, 'enviar'])
            ->name('plan-trabajo.enviar');
    });

    // Aprobar / Rechazar (Gerencia)
    Route::middleware(['auth', 'role:gerencia'])->group(function () {
        Route::post('plan-trabajo/{plan}/aprobar', [PlanTrabajoController::class, 'aprobar'])
            ->name('plan-trabajo.aprobar');

        Route::post('plan-trabajo/{plan}/rechazar', [PlanTrabajoController::class, 'rechazar'])
            ->name('plan-trabajo.rechazar');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__ . '/auth.php';
