<?php
namespace Projects\Core;

abstract class Route
{
    private static Router $router;

    public static function instance() {
      if (!isset(self::$router)) {
        self::$router = new Router();
      }
    }

    public static function get(string $path, $handler)
    {
        self::$router->get($path, $handler);
    }

    public static function post(string $path, $handler)
    {
        self::$router->post($path, $handler);
    }

    public static function put(string $path, $handler)
    {
        self::$router->put($path, $handler);
    }

    public static function delete(string $path, $handler)
    {
        self::$router->delete($path, $handler);
    }

    public static function dispatch() {
      self::$router->dispatch();
    }
}