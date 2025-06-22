<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1.0.0')->group(function () {
    Route::get('/', function (Request $request) {
        return 'e-commerce el-diego v1.0.0';
    });

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('products.destoy');
});
