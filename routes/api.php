<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('v1.0.0')->group(function () {
    Route::get('/', function (Request $request) {
        return 'e-commerce el-diego v1.0.0';
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products.index');
            Route::get('/{sku}', [ProductController::class, 'show'])->name('products.show');

            Route::middleware('role:admin')->group(function () {
                Route::post('/', [ProductController::class, 'store'])->name('products.store');
                Route::put('/{sku}', [ProductController::class, 'update'])->name('products.update');
                Route::delete('/{sku}', [ProductController::class, 'destroy'])->name('products.destoy');
            });
        });

        Route::prefix('orders')->group(function () {
            Route::post('/', [OrderController::class, 'store'])->name('orders.store');
            Route::get('{order}', [OrderController::class, 'show'])->name('orders.show');
        });
    });
});
