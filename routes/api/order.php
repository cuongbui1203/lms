<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index']);
Route::post('/', [OrderController::class, 'store']);

Route::prefix('multi')
    ->group(function () {
        Route::delete('/', [OrderController::class, 'destroy']);
        Route::put('shipping', [OrderController::class, 'shipping']);
        Route::get('next', [OrderController::class, 'getNextPos']);
        Route::post('next', [OrderController::class, 'moveToNextPos']);
        Route::put('arrived', [OrderController::class, 'arrivedPos']);
    });

Route::prefix('{order}')
    ->group(function () {
        Route::get('', [OrderController::class, 'show'])->withoutMiddleware('auth:sanctum');
        Route::post('', [OrderController::class, 'addDetail']);
        Route::put('/shipped', [OrderController::class, 'shipped']);
        Route::put('/vehicles/{vehicle}', [OrderController::class, 'ganChoXe']);
    });
