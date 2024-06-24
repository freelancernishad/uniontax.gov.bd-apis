<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenAuthController;

Route::post('/citizen/login', [CitizenAuthController::class, 'login']);
Route::post('/citizen/register', [CitizenAuthController::class, 'register']);

Route::middleware(['auth:citizen'])->group(function () {
    Route::post('/citizen/logout', [CitizenAuthController::class, 'logout']);
    Route::get('/citizen-access', function (Request $request) {
        return 'citizen access';
    });

    // Add other citizen routes
});
