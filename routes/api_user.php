<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('users/', [UserController::class,'index'])
    ->name('api.users.index');

Route::post('users', [UserController::class,'store'])
    ->name('api.users.register');

Route::get('users/{user}', [UserController::class,'show'])
    ->name('api.users.show');

Route::put('users/{users}', [UserController::class,'update'])
    ->name('api.users.update');
