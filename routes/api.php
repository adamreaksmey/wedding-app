<?php

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Items\CategoryController;
use App\Http\Controllers\Items\CouponController;
use App\Http\Controllers\Items\EventController;
use App\Http\Controllers\Items\EventUserController;

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
    Route::apiResource('event_user', EventUserController::class);
});

// Provinces
Route::get('/get_all_provinces', function () {
    return Helpers::all_provinces();
});
