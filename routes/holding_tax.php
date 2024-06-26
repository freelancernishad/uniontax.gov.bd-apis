<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\HoldingTax\HoldingtaxController;
use App\Http\Controllers\api\HoldingTax\HoldingBokeyaController;

Route::get('holding/bokeya/list', [HoldingBokeyaController::class, 'index']);
Route::post('get/holding/tax', [HoldingBokeyaController::class, 'holdingTaxPending']);
Route::post('holding/bokeya/edit/{id}', [HoldingBokeyaController::class, 'holdingTaxEdit']);

Route::post('holding/bokeya/action', [HoldingtaxController::class, 'holding_tax_pay']);
Route::get('holding/tax/list', [HoldingtaxController::class, 'index']);
Route::get('holding/tax/show/{id}', [HoldingtaxController::class, 'show']);
Route::get('holding/tax/delete/{id}', [HoldingtaxController::class, 'destroy']);
Route::post('holding/tax/submit', [HoldingtaxController::class, 'store']);

Route::post('holding/tax/search', [HoldingtaxController::class, 'holdingSearch']);
