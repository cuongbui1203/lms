<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\WorkPlateController;
use App\Models\Order;
use Illuminate\Http\Request;
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
    ->group(
        function () {

            Route::post('/login', [UserController::class, 'login'])
                ->name('.login');
            Route::post('', [UserController::class, 'store'])
                ->name('.register');

            Route::middleware('auth:sanctum')->group(
                function () {
                    Route::get('/me', [UserController::class, 'index'])
                        ->name('.index');
                    Route::get('/{user}', [UserController::class, 'show'])
                        ->name('.show');
                    Route::put('/{users}', [UserController::class, 'update'])
                        ->name('.update');
                    Route::get('/', [UserController::class, 'getListAccount']);
                    Route::put('/{user}/change-wp', [UserController::class, 'changeWP']);
                }
            );
        }
    );

Route::name('.image')
    ->prefix('images')
    ->group(
        function () {
            Route::get('/{image}', [ImageController::class, 'show'])->name('show');
        }
    );

Route::name('.work-plate')
    ->prefix('work-plates')
    ->middleware('auth:sanctum')
    ->group(
        function () {
            Route::post('/', [WorkPlateController::class, 'store'])->name('store');
            Route::put('/{workPlate}', [WorkPlateController::class, 'update'])->name('update');
            Route::delete('/{workPlate}', [WorkPlateController::class, 'destroy'])->name('destroy');
            Route::get('/', [WorkPlateController::class, 'index']);
            Route::get('/{workPlate}', [WorkPlateController::class, 'show']);
            Route::post('/{workPlate}/detail', [WorkPlateController::class, 'addDetail']);
        }
    );

Route::middleware(['auth:sanctum'])
    ->get('types/{for}', [TypeController::class, 'index']);

Route::prefix('/address')->group(function () {
    Route::get('/wards', [AddressController::class, 'getAllWards']);
    Route::get('/districts', [AddressController::class, 'getAllDistricts']);
    Route::get('/provinces', [AddressController::class, 'getAllProvinces']);
});

Route::prefix('vehicles')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/', [VehicleController::class, 'store']);
        Route::get('/', [VehicleController::class, 'index']);
        Route::post('/{vehicle}', [VehicleController::class, 'update']);
        Route::get('/{vehicle}', [VehicleController::class, 'show']);
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy']);
    });

Route::prefix('orders')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/', [OrderController::class, 'store']);
    });

Route::get('/test', function () {
    $order = Order::first();
    dd($order->routing($order));
});

Route::middleware(['auth:sanctum'])
    ->get('/token', function (Request $request) {
        return response()->json(['token' => $request->session()->token()]);
    });
