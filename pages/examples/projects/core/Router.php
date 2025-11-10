<?php
namespace Projects\Core;

class Router
{
    private array $routes = [];

    // Registrar rutas por método
    public function get(string $path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler)
    {
        // Normalizar también las rutas registradas
        // $path = rtrim($path, '/');
        // if ($path === '') $path = '/';

        // Obtener la ruta base (REQUEST_URI) al momento de agregar la ruta
        // $full_path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        // if ($path !== "") {
        //   $full_path .= $path;
        // }

        // $this->routes[$method][$full_path] = $handler;
        
        // Normalizar la ruta
        if ($path === '') {
            $path = '/';
        } elseif ($path[0] !== '/') {
            $path = '/' . $path;
        }

        // Eliminar slash final solo si no es la raíz
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        $this->routes[$method][$path] = $handler;
    }

    // Ejecutar la ruta correspondiente
    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Add
        // Quitar query string
        $uri = strtok($uri, '?');

        // Calcular el prefijo automáticamente según el script que se está ejecutando
        $script_name = $_SERVER['SCRIPT_NAME']; // ej: /simple_dashboard_php/pages/examples/projects/index.php
        $basePath = str_replace('/index.php', '', $script_name);

        // Quitar basePath de la URI
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        if ($uri === '') $uri = '/';
        // Normalizar
        if ($uri === '') $uri = '/';
        if ($uri[0] !== '/') $uri = '/' . $uri;
        if ($uri !== '/' && substr($uri, -1) === '/') $uri = rtrim($uri, '/');
        // EndAdd

        // ✅ Normalizar: eliminar "/" al final, excepto si la URI es solo "/"
        // if ($uri !== '/' && substr($uri, -1) === '/') {
        //     $uri = rtrim($uri, '/');
        // }

        $handler = null;
        $params = [];

        // ✅ Extraer query params (assoc)
        $queryParams = $_GET ?? [];

        // Buscar coincidencia exacta o con parámetros tipo /user/{id}
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $path => $h) {
                $pattern = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9_-]+)', $path);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // quitar coincidencia completa
                    $handler = $h;
                    $params = $matches;
                    break;
                }
            }
        }

        if (!$handler) {
            http_response_code(404);
            echo "<h1>404 - Página no encontrada</h1>".$path;
            return;
        }

        // Leer el body si corresponde
        $body = null;
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $body = json_decode(file_get_contents('php://input'), true);
        }

        // Ejecutar el handler
        $args = [];
        if (!empty($params)) {
            $args = [...$params];
        }
        if (!empty($queryParams)) {
            $args = [...$args, $queryParams, $body];
        } else {
            $args = [...$args, $body];
        }
        // $args = [...$params, $queryParams, $body];
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = new $class();
            $instance->$method(...$args);
        } else {
            $handler(...$args);
        }
    }
}