<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Profile\ChairmanController;
use App\Http\Controllers\Auth\chairman\ChairmanAuthController;

// Public routes
Route::post('/login', [ChairmanAuthController::class, 'login']);
Route::post('/register', [ChairmanAuthController::class, 'register']);

// Protected routes for authenticated chairmen
Route::middleware(['auth:chairman'])->group(function () {

    Route::post('/check-token', [ChairmanAuthController::class, 'checkToken']);
    Route::post('/check/login', [ChairmanAuthController::class, 'checkTokenExpiration']);
    Route::post('/logout', [ChairmanAuthController::class, 'logout']);

    Route::get('/access', function (Request $request) {
        return 'Chairman access';
    });

    Route::get('/profile', [ChairmanController::class, 'profile']);
    Route::post('/profile', [ChairmanController::class, 'updateProfile']);

    // Add other chairman-specific routes here

});
