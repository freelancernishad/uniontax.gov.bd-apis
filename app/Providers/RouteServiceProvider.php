<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot()
    {
        $this->configureRoutes();

        $this->routes(function () {
            // Register API routes
            Route::prefix('api')
                 ->middleware('api')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/api_routes.php'));

            // Register web routes
            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web.php'));

            // Register organization routes
            Route::prefix('api')
                 ->middleware(['api', 'auth:organization'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/organization_routes.php'));

            // Register admin routes
            Route::prefix('api')
                 ->middleware(['api', 'auth:admin'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/admin_routes.php'));

            // Register student routes
            Route::prefix('api')
                 ->middleware(['api', 'auth:student'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/student_routes.php'));

            // Register chairman routes
            Route::prefix('api')
                 ->middleware(['api', 'auth:chairman'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/chairman_routes.php'));

            // Register secretary routes
            Route::prefix('api')
                 ->middleware(['api', 'auth:secretary'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/secretary_routes.php'));
        });
    }

    /**
     * Configure your application's routes.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        // Optionally, you can add route model bindings, pattern filters, etc. here
    }
}
