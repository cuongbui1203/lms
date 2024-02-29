<?php

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
Route::get('users/', [UserController::class,'index'])
    ->name('api.users.index');

Route::post('users', [UserController::class,'store'])
    ->name('api.users.register');

Route::get('users/{user}', [UserController::class,'show'])
    ->name('api.users.show');

Route::put('users/{users}', [UserController::class,'update'])
    ->name('api.users.update');
