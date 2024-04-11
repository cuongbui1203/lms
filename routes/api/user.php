<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])
    ->middleware('session')
    ->name('.login');
Route::post('', [UserController::class, 'store'])
    ->name('.register');
Route::post('/forgot-password', [UserController::class, 'resetPasswordLink']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(
    function () {
        Route::put('/change-password', [UserController::class, 'changePassword']);
        Route::withoutMiddleware('csrf')
            ->delete('/me', [UserController::class, 'logout']);
        Route::get('/me', [UserController::class, 'index'])
            ->name('.index');
        Route::get('/{user}', [UserController::class, 'show'])
            ->name('.show');
        Route::put('/{user}', [UserController::class, 'update'])
            ->name('.update');
        Route::get('/', [UserController::class, 'getListAccount']);
        Route::put('/{user}/change-wp', [UserController::class, 'changeWP']);

        Route::middleware('adminManager')
            ->group(function () {
                Route::post('/create/employee', [UserController::class, 'createEmployee']);
            });
    }
);
