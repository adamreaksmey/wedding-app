<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


// AUTH
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth.jwt')->group(function () {
    // Get authorized user
    Route::get('user', [AuthController::class, 'get_authorized_user']);
    // logout
    Route::post('logout', [AuthController::class, 'logout']);
});
