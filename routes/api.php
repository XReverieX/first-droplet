<?php

use App\Http\Controllers\AuthenticationController;
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
        Route::get('/', [UserController::class, 'index'])->middleware('can:view users');

        // TODO: Update user details, 使用 policy 來處理 管理員可看全部 客戶只能看自己。查一下 laravel route 變數怎麼寫
        Route::put('/{id}', [UserController::class, 'update'])->middleware('can:view users, edit users');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('can:view users');
    });
});
