<?php

/**
 * Client Room API Routes
 * Accessible by all clients for browsing and searching rooms
 */

use App\Containers\ClientSection\Room\UI\API\Controllers\RoomController;
use App\Http\Middleware\LogRequestMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([LogRequestMiddleware::class])->group(function () {
    Route::get('rooms', [RoomController::class, 'listRooms'])
        ->name('client.api.rooms.list');

    Route::get('rooms/search', [RoomController::class, 'searchRooms'])
        ->name('client.api.rooms.search');

    Route::get('rooms/featured', [RoomController::class, 'getFeaturedRooms'])
        ->name('client.api.rooms.featured');

    Route::get('rooms/search/suggestions', [RoomController::class, 'getSearchSuggestions'])
        ->name('client.api.rooms.search.suggestions');

    Route::get('rooms/filters/popular', [RoomController::class, 'getPopularFilters'])
        ->name('client.api.rooms.filters.popular');

    Route::get('rooms/{id}', [RoomController::class, 'findRoomById'])
        ->name('client.api.rooms.find');
//    ->where('id', '[0-9]+');

    Route::get('rooms/{id}/availability', [RoomController::class, 'checkAvailability'])
        ->name('client.api.rooms.availability.check')
        ->where('id', '[0-9]+');

    Route::get('rooms/{id}/availability/calendar', [RoomController::class, 'getAvailabilityCalendar'])
        ->name('client.api.rooms.availability.calendar')
        ->where('id', '[0-9]+');

    Route::get('room-types', [RoomController::class, 'getRoomTypes'])
        ->name('client.api.room-types.list');
});

