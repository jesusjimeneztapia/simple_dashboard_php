<?php

$PROJECT_ROOT = realpath(__DIR__ . "/../..");
$DOCUMENT_ROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
$PROTOCOL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$HOST = $_SERVER['HTTP_HOST'];
$BASE_URL = $PROTOCOL . "://" . $HOST . str_replace('\\', '/', str_replace($DOCUMENT_ROOT, '', $PROJECT_ROOT));

function asset(string $path): string {
  global $PROJECT_ROOT, $DOCUMENT_ROOT;

  $base_url = str_replace('\\', '/', str_replace($DOCUMENT_ROOT, '', $PROJECT_ROOT)) . "/";
  return $base_url . ltrim($path, '/');
}

function base_url($root = false): string {
  if ($root) {
    global $BASE_URL;
    return $BASE_URL;
  }
  
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'];
  $script_name = $_SERVER['SCRIPT_NAME'];
  $basePath = str_replace('/index.php', '', $script_name);
  return $protocol . '://' . $host . $basePath;
}