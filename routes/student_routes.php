<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentController;

Route::post('/student/login', [StudentAuthController::class, 'login']);
Route::post('/student/register', [StudentAuthController::class, 'register']);

Route::middleware(['auth:student'])->group(function () {
    Route::post('/student/logout', [StudentAuthController::class, 'logout']);
    Route::get('/students/profile/{id}', [StudentController::class, 'show']);
    Route::get('/student-access', function (Request $request) {
        return 'student access';
    });
});
