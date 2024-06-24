<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\WeatherController;
use App\Models\Permission;

// Register dynamic permissions for routes
Route::get('get/all/route/name', function () {
    // Get all routes
    $routes = Route::getRoutes();

    foreach ($routes as $route) {
        $action = $route->getAction();

        // Check if middleware is 'checkPermission'
        if (isset($action['middleware']) && in_array('checkPermission', $action['middleware'])) {
            $routeName = $route->getName();

            if ($routeName && !Permission::where('path', $routeName)->exists()) {
                Permission::create([
                    'name' => $routeName,
                    'path' => $routeName,
                    // Add other attributes if needed
                ]);
            }
        }
    }
});

// Public routes
Route::get('/weather', [WeatherController::class, 'show']);

// Authentication routes
Route::post('/user/login', [AuthController::class, 'login'])->name('login');
Route::post('/user/register', [AuthController::class, 'register']);

// Authenticated routes
Route::middleware(['auth:api'])->group(function () {
    Route::post('/user/logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::prefix('users/role/system')->group(function () {
        Route::get('/', [RoleUserController::class, 'index']);
        Route::post('/', [RoleUserController::class, 'store']);
        Route::post('/{id}', [RoleUserController::class, 'update']);
        Route::get('/{id}', [RoleUserController::class, 'show']);
        Route::delete('/{id}', [RoleUserController::class, 'destroy']);
    });

    Route::post('users/change-password', [UserController::class, 'changePassword'])->name('users.change_password');
    Route::get('/user-access', function (Request $request) {
        return 'user access';
    })->name('user.access');

    Route::apiResources([
        'permissions' => PermissionController::class,
        // Add other API resources here
    ]);

    Route::post('/social-links', [SocialLinkController::class, 'store'])->name('social_links.store');
    Route::post('/social-links/{idOrPlatform}', [SocialLinkController::class, 'update'])->name('social_links.update');
    Route::delete('/social-links/{socialLink}', [SocialLinkController::class, 'destroy'])->name('social_links.destroy');

    Route::apiResources([
        'pages' => PageController::class,
        // Add other API resources here
    ]);

    Route::post('advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::delete('advertisements/{slug}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');

    // Add other authenticated routes
});
