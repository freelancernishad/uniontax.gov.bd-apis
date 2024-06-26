<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Profile\SecretaryController;
use App\Http\Controllers\Auth\secretary\SecretaryAuthController;

// Authentication routes
Route::post('/login', [SecretaryAuthController::class, 'login']); // Secretary login
Route::post('/register', [SecretaryAuthController::class, 'register']); // Secretary registration

// Authenticated routes for secretary
Route::middleware(['auth:secretary'])->group(function () {

    // Token management routes
    Route::post('/check-token', [SecretaryAuthController::class, 'checkToken']); // Check authentication token validity
    Route::post('/check/login', [SecretaryAuthController::class, 'checkTokenExpiration']); // Check login token expiration

    // Logout route
    Route::post('/logout', [SecretaryAuthController::class, 'logout']); // Secretary logout

    // Access route
    Route::get('/access', function (Request $request) {
        return 'secretary access'; // Access confirmation for secretary
    });

    // Profile routes
    Route::get('/profile', [SecretaryController::class, 'profile']); // View secretary profile
    Route::post('/profile', [SecretaryController::class, 'updateProfile']); // Update secretary profile

    // Add other secretary-specific routes here

});

