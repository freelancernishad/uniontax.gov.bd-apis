<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Geo\CountryApiController;

// country api
Route::get('/getdivisions', [CountryApiController::class,'getdivisions']);
Route::get('/getdistrict', [CountryApiController::class,'getdistrict']);
Route::get('/getthana', [CountryApiController::class,'getthana']);
Route::get('/getunioun', [CountryApiController::class,'getunioun']);
Route::get('/gotoUnion', [CountryApiController::class,'gotoUnion']);
