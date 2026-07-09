<?php

use Illuminate\Support\Facades\Route;
use App\Containers\ClientSection\Room\UI\API\Controllers\RoomController;

Route::get('check-timeout', [RoomController::class, 'checkTimeout'])
    ->name('api_check_timeout');
