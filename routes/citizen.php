<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Citizen\CitizenController;
use App\Http\Controllers\Auth\citizen\CitizenAuthController;
use App\Http\Controllers\api\Citizen\CitizenInformationController;

// Public routes
Route::post('/login', [CitizenAuthController::class, 'login']);
Route::post('/register', [CitizenAuthController::class, 'register']);

// Protected routes for authenticated citizens
Route::middleware(['auth:citizen'])->group(function () {

    Route::post('/check-token', [CitizenAuthController::class, 'checkToken']);
    Route::post('/check/login', [CitizenAuthController::class, 'checkTokenExpiration']);
    Route::post('/logout', [CitizenAuthController::class, 'logout']);
    Route::get('/access', function (Request $request) {
        return 'Citizen access';
    });

    // Add other citizen-specific routes here

});

// Routes for Citizen Information
Route::get('citizen/information/nid/extanal', [CitizenInformationController::class, 'citizeninformationNIDExtanal']);
Route::post('citizen/information/nid', [CitizenInformationController::class, 'citizeninformationNID']);
Route::post('citizen/information/brn', [CitizenInformationController::class, 'citizeninformationBRN']);

// CRUD routes for Citizen
Route::get('citizen/list', [CitizenController::class, 'index']);
Route::get('citizen/show/{id}', [CitizenController::class, 'show']);
Route::get('citizen/delete/{id}', [CitizenController::class, 'destroy']);
Route::post('citizen/submit', [CitizenController::class, 'store']);
