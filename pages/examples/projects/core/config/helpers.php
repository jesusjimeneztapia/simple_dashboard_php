<?php
function asset(string $path): string {
  $script_name = $_SERVER['SCRIPT_NAME'];
  $basePath = str_replace('/index.php', '', $script_name);
  return $basePath . '/../../../' . ltrim($path, '/');
}

function base_url(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/index.php', '', $script_name);
    return $protocol . '://' . $host . $basePath;
}