<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\citizen\CitizenAuthController;

Route::post('/login', [CitizenAuthController::class, 'login']);
Route::post('/register', [CitizenAuthController::class, 'register']);

Route::middleware(['auth:citizen'])->group(function () {



    Route::post('/check-token', [CitizenAuthController::class, 'checkToken']);
    Route::post('/check/login', [CitizenAuthController::class, 'checkTokenExpiration']);

    Route::post('/logout', [CitizenAuthController::class, 'logout']);
    Route::get('/access', function (Request $request) {
        return 'citizen access';
    });

    // Add other citizen routes
});
