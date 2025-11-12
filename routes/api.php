<?php

use SimpleDashboardPHP\Api\App\Http\Controllers\ProjectController;
use SimpleDashboardPHP\Core\Route;

Route::get("/api/projects", [ProjectController::class, "getAll"]);
Route::post("/api/projects", [ProjectController::class, "store"]);
Route::put("/api/projects/{projectId}", [ProjectController::class, "update"]);
Route::delete("/api/projects/{projectId}", [ProjectController::class, "destroy"]);