<?php

use App\Http\Controllers\Api\WorkPlateController;
use Illuminate\Support\Facades\Route;

Route::post('/', [WorkPlateController::class, 'store'])->name('store');
Route::put('/{workPlate}', [WorkPlateController::class, 'update'])->name('update');
Route::delete('/{workPlate}', [WorkPlateController::class, 'destroy'])->name('destroy');
Route::get('/', [WorkPlateController::class, 'index']);
Route::get('/{workPlate}', [WorkPlateController::class, 'show']);
Route::post('/{workPlate}/detail', [WorkPlateController::class, 'addDetail']);
