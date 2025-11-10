<?php

use Projects\App\Http\Controllers\ProjectController;
use Projects\Core\Route;

Route::get("/", [ProjectController::class, "index"]);
Route::get("/add", [ProjectController::class, "create"]);
Route::get("/edit/{projectId}", [ProjectController::class, "edit"]);
Route::get("/detail/{projectId}", [ProjectController::class, "show"]);