<?php

/**
 * Client Room API Routes (Authenticated)
 * Accessible by authenticated clients only
 */

use App\Containers\ClientSection\Room\UI\API\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

// These routes require authentication for personalized features
Route::get('rooms/personalized', [RoomController::class, 'getPersonalizedRooms'])
    ->name('client.api.rooms.personalized');

Route::post('rooms/{id}/bookmark', [RoomController::class, 'bookmarkRoom'])
    ->name('client.api.rooms.bookmark')
    ->where('id', '[0-9]+');

Route::delete('rooms/{id}/bookmark', [RoomController::class, 'removeBookmark'])
    ->name('client.api.rooms.bookmark.remove')
    ->where('id', '[0-9]+');

Route::get('rooms/bookmarked', [RoomController::class, 'getBookmarkedRooms'])
    ->name('client.api.rooms.bookmarked');

Route::post('rooms/{id}/view-history', [RoomController::class, 'addToViewHistory'])
    ->name('client.api.rooms.view-history.add')
    ->where('id', '[0-9]+');

Route::get('rooms/recently-viewed', [RoomController::class, 'getRecentlyViewedRooms'])
    ->name('client.api.rooms.recently-viewed');
