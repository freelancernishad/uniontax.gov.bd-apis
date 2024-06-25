<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Profile\SecretaryController;
use App\Http\Controllers\Auth\secretary\SecretaryAuthController;

Route::post('/login', [SecretaryAuthController::class, 'login']);
Route::post('/register', [SecretaryAuthController::class, 'register']);

Route::middleware(['auth:secretary'])->group(function () {



    Route::post('/check-token', [SecretaryAuthController::class, 'checkToken']);
    Route::post('/check/login', [SecretaryAuthController::class, 'checkTokenExpiration']);

    Route::post('/logout', [SecretaryAuthController::class, 'logout']);
    Route::get('/access', function (Request $request) {
        return 'secretary access';
    });

    Route::get('/profile', [SecretaryController::class, 'profile']);
    Route::post('/profile', [SecretaryController::class, 'updateProfile']);
    // Add other secretary-specific routes
});
