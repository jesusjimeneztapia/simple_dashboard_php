<?php
spl_autoload_register(function ($clase) {
    $namespace_base = 'SimpleDashboardPHP\\';
    $base_dir = __DIR__ . "/../../";

    if (strpos($clase, $namespace_base) === 0) {
        $relativa = substr($clase, strlen($namespace_base));
        $ruta = $base_dir . str_replace('\\', '/', $relativa) . '.php';

        if (file_exists($ruta)) {
            require $ruta;
        } else {
            error_log("No se encontró la clase: $clase en $ruta");
        }
    }
});
