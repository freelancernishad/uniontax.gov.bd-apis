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
                 ->group(base_path('routes/api.php'));

            // Register API routes
            Route::prefix('user')
                //  ->middleware('api')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/user.php'));

            // Register web routes
            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web.php'));

            // Register admin routes
            Route::prefix('admin')
                //  ->middleware(['api', 'auth:admin'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/admin.php'));


            // Register chairman routes
            Route::prefix('chairman')
                //  ->middleware(['api', 'auth:chairman'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/chairman.php'));

            // Register secretary routes
            Route::prefix('secretary')
                //  ->middleware(['api', 'auth:secretary'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/secretary.php'));


            // Register secretary routes
            Route::prefix('citizen')
                //  ->middleware(['api', 'auth:citizen'])
                 ->namespace($this->namespace)
                 ->group(base_path('routes/citizen.php'));

            // Register sonod routes
            Route::prefix('v1')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/sonod.php'));

            // Register sonod routes
            Route::prefix('v1')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/holding_tax.php'));


            // Register geo routes
            Route::prefix('geo')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/geo.php'));

            // Register geo routes
            Route::prefix('unioninfo')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/unioninfo.php'));


            // Register geo routes
            Route::prefix('tender')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/tender.php'));



            // Register geo routes
            Route::prefix('notification')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/notification.php'));




            // Register geo routes
            Route::prefix('api')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/payment.php'));



            // Register geo routes
            Route::prefix('api')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/visitor.php'));






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
