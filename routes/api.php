<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\VerifyAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/getme', [AuthController::class, 'getme']);

    Route::middleware(VerifyAdmin::class)->group(function () {
        Route::post('/categories', [CategoryController::class, 'create']);
        Route::patch('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'delete']);
        Route::post('products', [ProductController::class, 'create']);
        Route::patch('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'delete']);
        Route::get('/orders/all', [OrderController::class, 'index']);
    });
    Route::post('/orders', [OrderController::class, 'create']);
    Route::get('/orders', [OrderController::class, 'single']);
    Route::post('/favorite/{product}', [FavoriteController::class, 'create']);
    Route::get('/favorites', [FavoriteController::class, 'show']);
    Route::delete('/favorite/{product}', [FavoriteController::class, 'delete']);
    Route::get('/products', [ProductController::class, 'index']);
});
Route::get('/categories', [CategoryController::class, 'index']);
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/products/guest', [ProductController::class, 'index']);
    Route::post('/register', [AuthController::class, 'register']);
});
