<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Notification\NotificationsController;


Route::get('set/notification',[NotificationsController::class,'notifications']);
