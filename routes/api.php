<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/cart/items', [CartController::class, 'add']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::patch('/cart/items/{product}', [CartController::class, 'update']);
    Route::delete('/cart/items/{product}', [CartController::class, 'delete']);

    
    Route::post('/cart/checkout', [CartController::class, 'checkout']);


    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
