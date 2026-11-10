<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::middleware(['auth:sanctum', 'authorize-resources'])->group(function () {
    // ------------------------------------------------
    // categories
    Route::apiResource('categories', Controllers\API\CategoryController::class);

    // ------------------------------------------------
    // products
    Route::apiResource('products', Controllers\API\ProductController::class);
});
