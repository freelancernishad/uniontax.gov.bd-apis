<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretaryAuthController;
use App\Http\Controllers\SecretaryController;

Route::post('/secretary/login', [SecretaryAuthController::class, 'login']);
Route::post('/secretary/register', [SecretaryAuthController::class, 'register']);

Route::middleware(['auth:secretary'])->group(function () {
    Route::post('/secretary/logout', [SecretaryAuthController::class, 'logout']);
    Route::get('/secretary-access', function (Request $request) {
        return 'secretary access';
    });

    Route::get('/secretary/profile/{id}', [SecretaryController::class, 'show']);
    Route::put('/secretary/profile/{id}', [SecretaryController::class, 'update']);
    // Add other secretary-specific routes
});
