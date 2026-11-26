<?php

/*
 * Cualquier espacio, línea o BOM antes de "<?php" se manda al navegador en cada
 * respuesta. En HTML no se nota, pero corrompe imágenes, PDF y QR que se sirven
 * por PHP (pasó con 4 espacios al inicio de routes/web.php: la vista previa de
 * los adjuntos del calendario dejó de verse).
 */
it('no tiene nada antes de <?php en los archivos PHP del proyecto', function () {
    $raiz = dirname(__DIR__, 2);
    $malos = [];

    foreach (['app', 'bootstrap', 'config', 'database', 'routes'] as $carpeta) {
        $archivos = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("{$raiz}/{$carpeta}", FilesystemIterator::SKIP_DOTS));

        foreach ($archivos as $archivo) {
            $ruta = $archivo->getPathname();

            if (!str_ends_with($ruta, '.php') || str_contains($ruta, 'bootstrap' . DIRECTORY_SEPARATOR . 'cache')) {
                continue;
            }

            if (!str_starts_with(file_get_contents($ruta, false, null, 0, 5), '<?php')) {
                $malos[] = str_replace($raiz . DIRECTORY_SEPARATOR, '', $ruta);
            }
        }
    }

    expect($malos)->toBe([]);
});
