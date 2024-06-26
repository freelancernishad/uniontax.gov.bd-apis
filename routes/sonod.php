<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Sonod\SonodController;



Route::post('nagorik/seba/inserts', [SonodController::class, 'sonod_submit']);

// Route::post('nagorik/pre/pay/inserts', [SonodController::class, 'sonod_submit_pre_pay']);

Route::get('get/sonod/by/key', [SonodController::class, 'sonodByKey']);



Route::get('get/sonod/count', [SonodnamelistController::class, 'sonodCount']);

Route::post('prottoyon/update/{id}', [SonodController::class, 'prottonupdate']);

Route::get('sonod/verify/get', [SonodController::class, 'verifysonodId']);

Route::get('sonod/list', [SonodController::class, 'index']);

Route::post('role/assign', [authController::class, 'roleAssign']);

Route::get('sonod/single/{id}', [SonodController::class, 'singlesonod']);

Route::post('sonod/update', [SonodController::class, 'sonod_update']);

Route::get('sonod/delete/{id}', [SonodController::class, 'sonod_delete']);

Route::post('sonod/sec/approve/{id}', [SonodController::class, 'sec_sonod_action']);

Route::get('sonod/pay/{id}', [SonodController::class, 'sonod_pay']);

Route::post('sonod/cancel/{id}', [SonodController::class, 'cancelsonod']);

Route::get('sonod/{action}/{id}', [SonodController::class, 'sonod_action']);

Route::get('sonod/sonod_Id', [SonodController::class, 'sonod_id']);

Route::post('sonod/search', [SonodController::class, 'sonod_search']);

Route::get('get/prepaid/sonod', [SonodController::class, 'preapidSonod']);

Route::get('sonodcountall', [SonodController::class, 'sonodcountall']);

Route::get('sum/amount', [SonodController::class, 'totlaAmount']);

Route::get('count/sonod/{status}', [SonodController::class, 'counting']);

Route::get('niddob/verify', [SonodController::class, 'niddob']);
