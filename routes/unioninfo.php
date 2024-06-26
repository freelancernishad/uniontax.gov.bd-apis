<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Unioninfo\UniouninfoController;

// Create a new union
Route::post('unionCreate', [UniouninfoController::class, 'unionCreate']);

// Check NID service for a specific union
Route::post('nid/service/{union}', [UniouninfoController::class, 'unionservicecheck']);

// Check NID for a specific union
Route::post('nid/check/{union}', [UniouninfoController::class, 'unioncheck']);

// Get list of all unions
Route::get('get/union/list', [UniouninfoController::class, 'index']);

// Delete a union by ID
Route::get('get/union/delete/{id}', [UniouninfoController::class, 'deleteunion']);

// Get union details for update
Route::get('update/union/{id}', [UniouninfoController::class, 'getunion']);

// Route to update union information
Route::post('union/info', [UniouninfoController::class, 'unionInfo']);

// Route to submit updated union profile
Route::post('unionprofile/submit', [UniouninfoController::class, 'unionInfoUpdate']);

// Route to update payment information
Route::post('payment/update', [UniouninfoController::class, 'paymentUpdate']);

// Route for contacting the union
Route::post('contact', [UniouninfoController::class, 'contact']);
