<?php
namespace Projects\Core;

class Response
{
  private $data;
  private int $status = 200;
  private string $header;
  private static string $content_type_json = "Content-Type: application/json";

  private function __construct($data, string $header = "")
  {
    $this->data = $data;
    $this->header = $header ?: self::$content_type_json;
  }

  static function json($data)
  {
    return new Response($data, self::$content_type_json);
  }

  static function text($data)
  {
    return new Response($data);
  }

  public function status($status)
  {
    $this->status = $status;
    return $this;
  }

  public function send()
  {
    header($this->header);
    http_response_code($this->status);
    if ($this->header == self::$content_type_json) {
      echo json_encode($this->data);
    } else {
      echo $this->data;
    }
  }
}
