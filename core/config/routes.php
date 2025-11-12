<?php
use SimpleDashboardPHP\Core\Route;

Route::instance();

require_once __DIR__ . "/../../routes/api.php";
require_once __DIR__ . "/../../routes/web.php";

Route::dispatch();
