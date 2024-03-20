<?php

use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

Route::post('/', [VehicleController::class, 'store']);
Route::get('/', [VehicleController::class, 'index']);
Route::post('/{vehicle}', [VehicleController::class, 'update']);
Route::get('/{vehicle}', [VehicleController::class, 'show']);
Route::delete('/{vehicle}', [VehicleController::class, 'destroy']);
