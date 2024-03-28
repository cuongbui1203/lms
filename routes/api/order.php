<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/', [OrderController::class, 'store']);
Route::get('/{order}', [OrderController::class, 'show']);
Route::delete('/{order}', [OrderController::class, 'destroy']);
Route::post('/{order}', [OrderController::class, 'addDetail']);
Route::get('/{order}/next', [OrderController::class, 'getNextPos']);
Route::post('/{order}/next', [OrderController::class, 'moveToNextPos']);
