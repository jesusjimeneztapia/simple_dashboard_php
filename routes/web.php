<?php

use SimpleDashboardPHP\Core\Route;
use SimpleDashboardPHP\Pages\Examples\Projects\App\Http\Controllers\ProjectController;

Route::get("/pages/examples/projects", [ProjectController::class, "index"]);
Route::get("/pages/examples/projects/add", [ProjectController::class, "create"]);
Route::get("/pages/examples/projects/edit/{projectId}", [ProjectController::class, "edit"]);
Route::get("/pages/examples/projects/detail/{projectId}", [ProjectController::class, "show"]);