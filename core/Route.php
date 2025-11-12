<?php
namespace SimpleDashboardPHP\Core;

abstract class Route
{
    private static Router $router;
    private static string $base_url;

    public static function instance() {
      if (!isset(self::$router)) {
        self::$router = new Router();
        global $PROJECT_ROOT, $DOCUMENT_ROOT;

        self::$base_url = str_replace('\\', '/', str_replace($DOCUMENT_ROOT, '', $PROJECT_ROOT));
      }
    }

    public static function get(string $path, $handler)
    {
        self::$router->get(self::$base_url . $path, $handler);
    }

    public static function post(string $path, $handler)
    {
        self::$router->post(self::$base_url . $path, $handler);
    }

    public static function put(string $path, $handler)
    {
        self::$router->put(self::$base_url . $path, $handler);
    }

    public static function delete(string $path, $handler)
    {
        self::$router->delete(self::$base_url . $path, $handler);
    }

    public static function dispatch() {
      self::$router->dispatch();
    }
}