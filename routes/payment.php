<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Payment\PaymentController;

// IPN routes
Route::post('/ipn', [PaymentController::class, 'ipn']); // Handle IPN notifications
Route::post('/re/call/ipn', [PaymentController::class, 'ReCallIpn']); // Handle IPN recall
Route::post('/check/payments/ipn', [PaymentController::class, 'AkpayPaymentCheck']); // Check AKPAY payments

// Payment report routes
Route::get('payment/report/search', [PaymentController::class, 'Search']); // Display payment report search form (GET)
Route::post('payment/report/search', [PaymentController::class, 'Search']); // Process payment report search (POST)
Route::get('payment/ekpay/report/search', [PaymentController::class, 'SearchByAll']); // Search payments via EKPAY

// Example of a commented-out route:
// Route::post('online/payment/report/search', [PaymentController::class, 'onlinePaymentSearch']); // Search online payments (POST)
