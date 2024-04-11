<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index']);
Route::post('/', [OrderController::class, 'store']);
Route::get('/multi/next', [OrderController::class, 'getNextPos']);
Route::post('/multi/next', [OrderController::class, 'moveToNextPos']);
Route::put('/multi/arrived', [OrderController::class, 'arrivedPos']);
Route::delete('/multi', [OrderController::class, 'destroy']);
Route::get('/{order}', [OrderController::class, 'show']);
Route::post('/{order}', [OrderController::class, 'addDetail']);
Route::put('/{order}/vehicles/{vehicle}', [OrderController::class, 'ganChoXe']);
