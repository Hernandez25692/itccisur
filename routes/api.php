<?php

use Illuminate\Support\Facades\Route;
use App\Models\ValidacionQr;

Route::get('/validar/{token}', function ($token) {
    $registro = ValidacionQr::where('token', $token)->first();

    if (!$registro) {
        return response()->json([
            'success' => false,
            'valido' => false,
            'mensaje' => 'Registro no encontrado',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'valido' => (bool) $registro->activo,
        'nombre_formacion' => $registro->nombre_formacion,
        'fecha_formacion' => $registro->fecha_formacion,
        'anio' => $registro->anio,
        'token' => $registro->token,
    ]);
});
