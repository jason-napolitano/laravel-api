<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::middleware(['auth:sanctum', 'authorize-resources'])->group(function () {
    Route::apiResources([
        'permissions' => Controllers\API\PermissionController::class,
        'categories'  => Controllers\API\CategoryController::class,
        'products'    => Controllers\API\ProductController::class,
        'roles'       => Controllers\API\RoleController::class,
    ]);
});
