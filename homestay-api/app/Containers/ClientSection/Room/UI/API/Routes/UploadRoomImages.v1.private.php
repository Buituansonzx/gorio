<?php

/**
 * Room Image Upload API Routes
 * Accessible by authenticated users for uploading room images
 */

use App\Containers\ClientSection\Room\UI\API\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('rooms/images', [RoomController::class, 'uploadImages'])
    ->name('client.api.rooms.upload-images');
