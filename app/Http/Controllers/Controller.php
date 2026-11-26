<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Archivos subidos (evidencias, adjuntos, comprobantes): se guardan en el
     * disco privado y solo se sirven por una ruta con sesión y rol. Los que
     * se subieron antes al disco público se siguen encontrando hasta correr
     * `php artisan archivos:privatizar`.
     */
    protected function mostrarArchivo(?string $ruta)
    {
        abort_unless($ruta, 404);

        foreach (['local', 'public'] as $disco) {
            if (Storage::disk($disco)->exists($ruta)) {
                return Storage::disk($disco)->response($ruta);
            }
        }

        // Deja rastro en storage/logs: casi siempre es un archivo que no se copió
        // al servidor o una carpeta sin permiso de lectura para el usuario web
        $rutas = [Storage::disk('local')->path($ruta), Storage::disk('public')->path($ruta)];
        Log::warning('Archivo adjunto no disponible', [
            'ruta' => $ruta,
            'buscado_en' => $rutas,
            'carpetas_legibles' => array_map(fn ($r) => is_dir(dirname($r)) ? is_readable(dirname($r)) : 'no existe', $rutas),
            'usuario_php' => function_exists('posix_geteuid') ? (posix_getpwuid(posix_geteuid())['name'] ?? null) : get_current_user(),
        ]);

        abort(404);
    }

    protected function eliminarArchivo(?string $ruta): void
    {
        if ($ruta) {
            Storage::disk('local')->delete($ruta);
            Storage::disk('public')->delete($ruta);
        }
    }
}
