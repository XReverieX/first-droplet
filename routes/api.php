<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'ok';
});

Route::post('user', [UserController::class, 'store']);
Route::post('login', [AuthenticationController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);

        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);

        Route::put('/{id}/role', [UserController::class, 'assignRole']);
    });

    Route::prefix('role')->group(function () {
        // TODO: Use model policy to control the access to this route.
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
    });
});
