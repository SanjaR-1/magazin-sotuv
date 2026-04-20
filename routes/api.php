<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:customer')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/my-orders', [OrderController::class, 'index']);
    });
    Route::middleware('role:driver')->group(function () {
        Route::get('/driver-orders', [OrderController::class, 'index']);
        Route::get('/packing-deliveries', [OrderController::class, 'packingDeliveries']);
        Route::put('/orders/{order}/take', [OrderController::class, 'takeOrder']);
        Route::patch('/orders/{order}/deliver', [OrderController::class, 'deliverOrder']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::get('/orders', [OrderController::class, 'index']);

        Route::get('/reports/driver-stats', [ReportController::class, 'driverStats']);
        Route::get('/reports/product-sales', [ReportController::class, 'productSales']);
        Route::get('/reports/region-sales', [ReportController::class, 'regionSales']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});