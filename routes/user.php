<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\Auth\users\AuthController;

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

// Authenticated routes
Route::middleware(['auth:api'])->group(function () {

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

    // Role and User management routes under '/role/system'
    Route::prefix('/role/system')->group(function () {
        Route::get('/', [RoleUserController::class, 'index']);
        Route::post('/', [RoleUserController::class, 'store']);
        Route::post('/{id}', [RoleUserController::class, 'update']);
        Route::get('/{id}', [RoleUserController::class, 'show']);
        Route::delete('/{id}', [RoleUserController::class, 'destroy']);
    });

    // Change password route
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('users.change_password');

    // Access route for authenticated users
    Route::get('/access', function (Request $request) {
        return 'user access';
    })->name('user.access');

    // API resource routes
    Route::apiResources([
        'permissions' => PermissionController::class,
        // Add other API resources here
    ]);

    // Social links routes
    Route::post('/social-links', [SocialLinkController::class, 'store'])->name('social_links.store');
    Route::post('/social-links/{idOrPlatform}', [SocialLinkController::class, 'update'])->name('social_links.update');
    Route::delete('/social-links/{socialLink}', [SocialLinkController::class, 'destroy'])->name('social_links.destroy');

    // API resource routes for pages
    Route::apiResources([
        'pages' => PageController::class,
        // Add other API resources here
    ]);

    // Advertisement routes
    Route::post('advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::delete('advertisements/{slug}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');

    // Add other authenticated routes
});

