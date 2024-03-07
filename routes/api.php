<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get(
    '/user', function (Request $request) {
        return $request->user();
    }
);
Route::name('.users')
    ->prefix('users')
    ->group(
        function () {

            Route::post('/login', [UserController::class,'login'])
            ->name('.login');
            Route::post('', [UserController::class,'store'])
            ->name('.register');

            Route::middleware('auth:sanctum')->group(
                function () {
                    Route::get('/me', [UserController::class,'index'])
                    ->name('.index');
                    Route::get('/{user}', [UserController::class,'show'])
                    ->name('.show');

                    Route::put('/{users}', [UserController::class,'update'])
                    ->name('.update');
                }
            );

        }
    );

Route::name('.image')
    ->prefix('images')
    ->group(
        function () {
            Route::get('/', [ImageController::class,'show']);
        }
    );
