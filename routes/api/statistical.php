<?php

use App\Http\Controllers\Api\StatisticalController;

Route::get('/employees', [StatisticalController::class, 'getTotalEmployee']);
Route::get('/orders', [StatisticalController::class, 'getOrder']);
Route::get('/revenue', [StatisticalController::class, 'getTotalRevenue']);
