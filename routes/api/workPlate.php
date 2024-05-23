<?php

use App\Http\Controllers\Api\WorkPlateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WorkPlateController::class, 'index']);
Route::post('/', [WorkPlateController::class, 'store'])->name('store');
Route::get('/suggestion-wp', [WorkPlateController::class, 'getSuggestionWP']);
Route::put('/{workPlate}', [WorkPlateController::class, 'update'])->name('update');
Route::delete('/{workPlate}', [WorkPlateController::class, 'destroy'])->name('destroy');
Route::get('/{workPlate}', [WorkPlateController::class, 'show']);
Route::get('/{wp}/orders', [WorkPlateController::class, 'getOrderSend']);
Route::put('/{workPlate}/detail', [WorkPlateController::class, 'addDetail']);
