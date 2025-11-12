<?php
namespace SimpleDashboardPHP\Core;

class Router
{
    private array $routes = [];

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
        if ($path === '') {
            $path = '/';
        } elseif ($path[0] !== '/') {
            $path = '/' . $path;
        }

        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = strtok($uri, '?');

        // $script_name = $_SERVER['SCRIPT_NAME'];
        // $basePath = str_replace('/index.php', '', $script_name);

        // if (strpos($uri, $basePath) === 0) {
        //     $uri = substr($uri, strlen($basePath));
        // }
        if ($uri === '') $uri = '/';
        if ($uri === '') $uri = '/';
        if ($uri[0] !== '/') $uri = '/' . $uri;
        if ($uri !== '/' && substr($uri, -1) === '/') $uri = rtrim($uri, '/');

        $handler = null;
        $params = [];

        $queryParams = $_GET ?? [];

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $path => $h) {
                $pattern = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9_-]+)', $path);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
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

        $body = null;
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $body = json_decode(file_get_contents('php://input'), true);
        }

        $args = [];
        if (!empty($params)) {
            $args = [...$params];
        }
        if (!empty($queryParams)) {
            $args = [...$args, $queryParams, $body];
        } else {
            $args = [...$args, $body];
        }
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = new $class();
            $instance->$method(...$args);
        } else {
            $handler(...$args);
        }
    }
}