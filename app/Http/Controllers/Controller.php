<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
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
