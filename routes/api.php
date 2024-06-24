<?php

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MacController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\api\StudentController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Auth\users\AuthController;
use App\Http\Controllers\api\OrganizationController;
use App\Http\Controllers\Auth\admins\AdminAuthController;
use App\Http\Controllers\Auth\students\StudentAuthController;
use App\Http\Controllers\Auth\orgs\OrganizationAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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
Route::post('/organization/login', [OrganizationAuthController::class, 'login']);
Route::post('/organization/register', [OrganizationAuthController::class, 'register']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/register', [AdminAuthController::class, 'register']);
Route::post('/student/login', [StudentAuthController::class, 'login']);
Route::post('/student/register', [StudentAuthController::class, 'register']);
Route::post('/chairman/login', [ChairmanAuthController::class, 'login']);
Route::post('/chairman/register', [ChairmanAuthController::class, 'register']);
Route::post('/secretary/login', [SecretaryAuthController::class, 'login']);
Route::post('/secretary/register', [SecretaryAuthController::class, 'register']);

// Authenticated routes
Route::group(['middleware' => ['auth:api']], function () {
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

// Routes for organization and admin after authentication
Route::group(['middleware' => ['auth:organization']], function () {
    Route::post('/organization/logout', [OrganizationAuthController::class, 'logout']);

    Route::prefix('organizations')->group(function () {
        Route::put('{id}', [OrganizationController::class, 'update']);
        Route::delete('{id}', [OrganizationController::class, 'delete']);
        Route::get('{id}', [OrganizationController::class, 'show']);
    });

    Route::post('organization/doners', [OrganizationController::class, 'getDonersByOrganization']);
    Route::post('organization/change-password', [OrganizationController::class, 'changePassword']);
});

Route::group(['middleware' => ['auth:admin']], function () {
    Route::post('admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin-access', function (Request $request) {
        return 'admin access';
    });

    // Add other admin routes
});

// Student specific routes
Route::middleware(['auth:student'])->group(function () {
    Route::post('/student/logout', [StudentAuthController::class, 'logout']);
    Route::get('/students/profile/{id}', [StudentController::class, 'show']);
    Route::get('/student-access', function (Request $request) {
        return 'student access';
    });
});

// Chairman specific routes
Route::group(['middleware' => ['auth:chairman']], function () {
    Route::post('chairman/logout', [ChairmanAuthController::class, 'logout']);
    Route::get('/chairman-access', function (Request $request) {
        return 'chairman access';
    });

    Route::get('/chairman/profile/{id}', [ChairmanController::class, 'show']);
    Route::put('/chairman/profile/{id}', [ChairmanController::class, 'update']);
    // Add other chairman-specific routes
});

// Secretary specific routes
Route::group(['middleware' => ['auth:secretary']], function () {
    Route::post('secretary/logout', [SecretaryAuthController::class, 'logout']);
    Route::get('/secretary-access', function (Request $request) {
        return 'secretary access';
    });

    Route::get('/secretary/profile/{id}', [SecretaryController::class, 'show']);
    Route::put('/secretary/profile/{id}', [SecretaryController::class, 'update']);
    // Add other secretary-specific routes
});

// Additional routes as needed

