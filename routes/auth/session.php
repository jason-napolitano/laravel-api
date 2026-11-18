<?php

use App\Http\Controllers\Auth\Session\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        // ------------------------------------------------
        // login
        Route::post('login', [AuthenticationController::class, 'authenticate'])
            ->middleware(['throttle:login'])
            ->name('auth.session.login');

        // ------------------------------------------------
        // register
        Route::post('/register', [AuthenticationController::class, 'register'])
            ->name('auth.session.register');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // ------------------------------------------------
        // logout
        Route::post('logout', [AuthenticationController::class, 'destroy'])
            ->name('auth.session.destroy');

        // ------------------------------------------------
        // get user
        Route::get('user', [AuthenticationController::class, 'user'])
            ->name('auth.session.user');
    });

});
