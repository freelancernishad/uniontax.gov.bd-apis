<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Visitor\VisitorController;

Route::post('visitorcreate',[VisitorController::class, 'visitorcreate']);
Route::get('visitorcount',[VisitorController::class, 'visitorCount']);
