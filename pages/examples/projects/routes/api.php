<?php

use Projects\App\Http\Controllers\Api\ProjectApiController;
use Projects\Core\Route;

Route::post("/api", [ProjectApiController::class, "store"]);
Route::put("/api/{projectId}", [ProjectApiController::class, "update"]);
Route::delete("/api/{projectId}", [ProjectApiController::class, "destroy"]);