<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChairmanAuthController;
use App\Http\Controllers\ChairmanController;

Route::post('/chairman/login', [ChairmanAuthController::class, 'login']);
Route::post('/chairman/register', [ChairmanAuthController::class, 'register']);

Route::middleware(['auth:chairman'])->group(function () {
    Route::post('/chairman/logout', [ChairmanAuthController::class, 'logout']);
    Route::get('/chairman-access', function (Request $request) {
        return 'chairman access';
    });

    Route::get('/chairman/profile/{id}', [ChairmanController::class, 'show']);
    Route::put('/chairman/profile/{id}', [ChairmanController::class, 'update']);
    // Add other chairman-specific routes
});
