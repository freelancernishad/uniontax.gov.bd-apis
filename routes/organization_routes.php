<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationAuthController;
use App\Http\Controllers\OrganizationController;

Route::post('/organization/login', [OrganizationAuthController::class, 'login']);
Route::post('/organization/register', [OrganizationAuthController::class, 'register']);

Route::middleware(['auth:organization'])->group(function () {
    Route::post('/organization/logout', [OrganizationAuthController::class, 'logout']);

    Route::prefix('organizations')->group(function () {
        Route::put('{id}', [OrganizationController::class, 'update']);
        Route::delete('{id}', [OrganizationController::class, 'delete']);
        Route::get('{id}', [OrganizationController::class, 'show']);
    });

    Route::post('organization/doners', [OrganizationController::class, 'getDonersByOrganization']);
    Route::post('organization/change-password', [OrganizationController::class, 'changePassword']);
});
