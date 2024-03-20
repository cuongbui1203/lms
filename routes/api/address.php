<?php

use App\Http\Controllers\Api\AddressController;
use Illuminate\Support\Facades\Route;

Route::get('/wards', [AddressController::class, 'getAllWards']);
Route::get('/districts', [AddressController::class, 'getAllDistricts']);
Route::get('/provinces', [AddressController::class, 'getAllProvinces']);
