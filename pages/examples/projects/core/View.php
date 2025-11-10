<?php
namespace Projects\Core;

class View
{
  private static $path = "/../views/";
  private static $default_extension = ".php";

  static function render($template, $data = []) {
    $base_dir = __DIR__.self::$path;
    $ext = self::$default_extension;

    $head = $data["head"] ?? [];
    $body = $data["body"] ?? [];

    if (!empty($body["content"])) {
      $content_file = $base_dir.$body["content"].$ext;

      if (file_exists($content_file)) {
        ob_start();
        extract($body["data"] ?? []);
        require $content_file;
        $body["content_html"] = ob_get_clean();
      } else {
        throw new \Exception("Vista del contenido no encontrada: $content_file");
      }
    } else {
      extract($data);
    }

    $layout_file = $base_dir.$template.$ext;
    if (!file_exists($layout_file)) {
      throw new \Exception("Layout no encontrado: $layout_file");
    }

    require $layout_file;
  }
}