<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Sonod\SonodController;
use App\Http\Controllers\api\Sonod\CharageController;
use App\Http\Controllers\api\Sonod\ActionLogController;
use App\Http\Controllers\api\Sonod\SonodnamelistController;
use App\Http\Controllers\api\Expenditure\ExpenditureController;
use App\Http\Controllers\api\TradeLicense\TradeLicenseKhatController;
use App\Http\Controllers\api\TradeLicense\TradeLicenseKhatFeeController;

// Sonod routes
Route::post('nagorik/seba/inserts', [SonodController::class, 'sonod_submit']); // Submit sonod for citizens

Route::get('get/sonod/by/key', [SonodController::class, 'sonodByKey']); // Get sonod details by key

// Sonodnamelist routes
Route::get('get/sonod/count', [SonodnamelistController::class, 'sonodCount']); // Get count of sonods
Route::get('sonod/fee/list', [SonodnamelistController::class,'feeList']); // List sonod fees

Route::get('get/sonodname/list', [SonodnamelistController::class,'index']); // Get sonod name list
Route::get('get/sonodname/delete/{id}', [SonodnamelistController::class,'deletesonodname']); // Delete sonod name

Route::get('update/sonodname/{id}', [SonodnamelistController::class,'getsonodname']); // Get sonod name for update
Route::post('update/sonodname', [SonodnamelistController::class,'updatesonodname']); // Update sonod name
Route::post('sonod/fee/setup', [SonodnamelistController::class,'updatesonodnameFee']); // Setup sonod fee

// Other SonodController routes
Route::post('prottoyon/update/{id}', [SonodController::class, 'prottonupdate']); // Update sonod by ID

Route::get('sonod/verify/get', [SonodController::class, 'verifysonodId']); // Verify sonod ID

Route::get('sonod/list', [SonodController::class, 'index']); // List sonods

Route::get('sonod/single/{id}', [SonodController::class, 'singlesonod']); // Get single sonod details by ID
Route::post('sonod/update', [SonodController::class, 'sonod_update']); // Update sonod details
Route::get('sonod/delete/{id}', [SonodController::class, 'sonod_delete']); // Delete sonod by ID

Route::post('sonod/sec/approve/{id}', [SonodController::class, 'sec_sonod_action']); // Secretary approve sonod by ID
Route::get('sonod/pay/{id}', [SonodController::class, 'sonod_pay']); // Pay sonod by ID
Route::post('sonod/cancel/{id}', [SonodController::class, 'cancelsonod']); // Cancel sonod by ID

Route::get('sonod/{action}/{id}', [SonodController::class, 'sonod_action']); // Perform sonod action by ID
Route::get('sonod/sonod_Id', [SonodController::class, 'sonod_id']); // Get sonod ID

Route::post('sonod/search', [SonodController::class, 'sonod_search']); // Search sonods

Route::get('get/prepaid/sonod', [SonodController::class, 'preapidSonod']); // Get prepaid sonods

Route::get('sonodcountall', [SonodController::class, 'sonodcountall']); // Count all sonods

Route::get('sum/amount', [SonodController::class, 'totlaAmount']); // Sum total amount

Route::get('count/sonod/{status}', [SonodController::class, 'counting']); // Count sonods by status

Route::get('niddob/verify', [SonodController::class, 'niddob']); // Verify NID/DOB

Route::get('akpay', [SonodController::class, 'akpay']); // Akpay route

// TradeLicense routes
Route::resources([
    'tradeLicenseKhat' => TradeLicenseKhatController::class, // Trade license khat resource routes
    'tradeLicenseKhatFee' => TradeLicenseKhatFeeController::class, // Trade license khat fee resource routes
]);

// ActionLog routes
Route::get('reject/{id}', [ActionLogController::class, 'rejectreason']); // Get reject reason by ID

// CharageController routes
Route::post('vattax/get', [CharageController::class, 'index']); // Get VAT/Tax details
Route::post('vattax/submit', [CharageController::class, 'store']); // Submit VAT/Tax details

// ExpenditureController routes
Route::get('cash/expenditure', [ExpenditureController::class, 'index']); // List cash expenditures
Route::post('cash/expenditure', [ExpenditureController::class, 'store']); // Store cash expenditure

