<?php

use App\Http\Controllers\Auth as AuthControllers;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        // ------------------------------------------------
        // login
        Route::post('login', [AuthControllers\Token\AuthenticationController::class, 'authenticate'])
            ->name('auth.login');

        // ------------------------------------------------
        // register
        Route::post('register', [AuthControllers\Token\AuthenticationController::class, 'register'])
            ->name('auth.register');

        // ------------------------------------------------
        // forgot password
        Route::post('forgot-password', [AuthControllers\PasswordResetController::class, 'sendResetLink'])
            ->name('auth.forgot_password')
            ->middleware('throttle:5,1');

        // ------------------------------------------------
        // reset password
        Route::post('reset-password', [AuthControllers\PasswordResetController::class, 'resetPassword'])
            ->name('auth.reset_password')
            ->middleware('throttle:5,1');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // ------------------------------------------------
        // logout of current device
        Route::post('logout', [AuthControllers\Token\AuthenticationController::class, 'destroy'])
            ->name('auth.logout');

        // ------------------------------------------------
        // logout of all devices
        Route::post('logout-all', [AuthControllers\Token\AuthenticationController::class, 'destroyAll'])
            ->name('auth.logout-all');
    });
});
