<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\TypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::name('.users')
    ->prefix('users')
    ->group(api_path('user.php'));

Route::name('.work-plate')
    ->prefix('work-plates')
    ->middleware('auth:sanctum')
    ->group(api_path('workPlate.php'));

Route::prefix('/address')
    ->group(api_path('address.php'));

Route::prefix('vehicles')
    ->middleware(['auth:sanctum'])
    ->group(api_path('vehicle.php'));

Route::prefix('orders')
    ->middleware(['auth:sanctum'])
    ->group(api_path('order.php'));

Route::name('.image')
    ->prefix('images')
    ->get('/{image}', [ImageController::class, 'show'])->name('.show');

Route::middleware(['auth:sanctum'])->get('types/{for}', [TypeController::class, 'index']);
