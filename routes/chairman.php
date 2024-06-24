<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChairmanController;
use App\Http\Controllers\Auth\chairman\ChairmanAuthController;


Route::post('/login', [ChairmanAuthController::class, 'login']);
Route::post('/register', [ChairmanAuthController::class, 'register']);

Route::middleware(['auth:chairman'])->group(function () {

    Route::post('/check-token', [ChairmanAuthController::class, 'checkToken']);
    Route::post('/check/login', [ChairmanAuthController::class, 'checkTokenExpiration']);

    Route::post('/logout', [ChairmanAuthController::class, 'logout']);
    Route::get('/access', function (Request $request) {
        return 'chairman access';
    });

    Route::get('/profile/{id}', [ChairmanController::class, 'show']);
    Route::put('/profile/{id}', [ChairmanController::class, 'update']);
    // Add other chairman-specific routes
});
