<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Items\CategoryController;
use App\Http\Controllers\Items\CouponController;
use App\Http\Controllers\Items\EventController;

// AUTH
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth.jwt')->group(function () {
    // Get authorized user
    Route::get('user', [AuthController::class, 'get_authorized_user']);
    // logout
    Route::post('logout', [AuthController::class, 'logout']);

    // Items
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('coupon', CouponController::class);
    Route::apiResource('event', EventController::class);
});
