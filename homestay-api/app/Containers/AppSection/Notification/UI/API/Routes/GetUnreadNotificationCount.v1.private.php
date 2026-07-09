<?php

use App\Containers\AppSection\Notification\UI\API\Controllers\GetUnreadNotificationCountController;
use Illuminate\Support\Facades\Route;

Route::get('notifications/unread/count', GetUnreadNotificationCountController::class)
    ->middleware(['auth:api']);
