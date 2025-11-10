<?php
spl_autoload_register(function ($clase) {
    // Namespace base que corresponde al módulo Projects
    $namespace_base = 'Projects\\';
    $base_dir = __DIR__ . '/../../';

    // Solo autoload si la clase empieza con ese namespace
    if (strpos($clase, $namespace_base) === 0) {
        // Quitamos el namespace base
        $relativa = substr($clase, strlen($namespace_base));
        // Convertimos "\" en "/"
        $ruta = $base_dir . str_replace('\\', '/', $relativa) . '.php';

        if (file_exists($ruta)) {
            require $ruta;
        } else {
            error_log("No se encontró la clase: $clase en $ruta");
        }
    }
});
