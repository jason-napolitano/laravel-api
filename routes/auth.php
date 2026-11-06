<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        // ------------------------------------------------
        // login
        Route::post('login', [Controllers\Auth\AuthController::class, 'authenticate'])
            ->name('auth.login');

        // ------------------------------------------------
        // register
        Route::post('register', [Controllers\Auth\AuthController::class, 'register'])
            ->name('auth.register');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // ------------------------------------------------
        // logout of current device
        Route::post('logout', [Controllers\Auth\AuthController::class, 'destroy'])
            ->name('auth.logout');

        // ------------------------------------------------
        // logout of all devices
        Route::post('logout-all', [Controllers\Auth\AuthController::class, 'destroyAll'])
            ->name('auth.logout-all');
    });
});
