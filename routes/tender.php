<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Tender\TenderListController;
use App\Http\Controllers\api\Tender\TanderInvoiceController;
use App\Http\Controllers\api\Tender\TenderFormBuyController;

// Resourceful routes for TenderListController and TenderFormBuyController
Route::resources([
    'tender' => TenderListController::class,        // TenderList resource routes
    'tenderform' => TenderFormBuyController::class, // TenderFormBuy resource routes
]);

// Custom routes for TenderListController
Route::post('tender/selection/{tender_id}', [TenderListController::class, 'SeletionTender']); // Select tender by ID
Route::post('committe/update/{id}', [TenderListController::class, 'updateCommittee']); // Update committee details by ID
Route::get('get/all/applications/{tender_id}', [TenderListController::class, 'getAllApplications']); // Get all applications for a tender
Route::get('get/all/tender/list', [TenderListController::class, 'getAllTenderLists']); // Get all tender lists
Route::get('get/single/tender/{id}', [TenderListController::class, 'getSingleTender']); // Get single tender details by ID

// Resourceful routes for TanderInvoiceController
Route::apiResource('tander_invoices', TanderInvoiceController::class); // API resource routes for tender invoices

// Custom route for TanderInvoiceController
Route::get('tender/payment/{tender_id}', [TanderInvoiceController::class, 'tanderDepositAmount']); // Get tender deposit amount by ID

