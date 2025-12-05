    <?php

    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\PlanTrabajoController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\PlanActividadController;

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
