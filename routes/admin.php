<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\admins\AdminAuthController;

Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/register', [AdminAuthController::class, 'register']);

Route::middleware(['auth:admin'])->group(function () {

    Route::post('/check-token', [AdminAuthController::class, 'checkToken']);
    Route::post('/check/login', [AdminAuthController::class, 'checkTokenExpiration']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);



    Route::get('/access', function (Request $request) {
        return 'admin access';
    });

    // Add other admin routes
});
