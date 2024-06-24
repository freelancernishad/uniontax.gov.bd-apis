<?php


use Illuminate\Support\Facades\Route;

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
