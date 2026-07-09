<?php

namespace App\Containers\MobileSection\Room\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\MobileSection\Room\Actions\FindRoomByIdAction;
use App\Containers\MobileSection\Room\Actions\ListRoomsAction;
use App\Containers\MobileSection\Room\UI\API\Requests\FindRoomByIdRequest;
use App\Containers\MobileSection\Room\UI\API\Requests\ListRoomRequest;
use App\Containers\MobileSection\Room\UI\API\Transformers\RoomByIdTransformer;
use App\Containers\MobileSection\Room\UI\API\Transformers\RoomTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class RoomController extends ApiController
{
    public function listRooms(ListRoomRequest $request, ListRoomsAction $action): JsonResponse
    {

        $filters = $request->only([
            'min_price',
            'max_price',
            'capacity',
            'room_type_id',
            'check_in',
            'check_out',
            'amenities',
            'district_id',
            'province_id',
            'is_by_hours',
            'code',
            'check_in_time',
            'check_out_time',
            'max_guests',
            'projector',
            'bathtub',
            'is_loved_by_everyone',
            'self_checkin',
            'bathroom_count',
            'bed_count',
            'bedroom_count',
            'parking_for_car',
            'duplex',
            'balcony'
        ]);
        $rooms = $action->run($filters);

        return Response::create($rooms, RoomTransformer::class)->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Management
     *
     * Find Room by ID
     *
     * Get detailed information about a specific room.
     */
    public function findRoomById(FindRoomByIdRequest $request, FindRoomByIdAction $action): JsonResponse
    {
        $room = $action->run($request);

        return Response::create($room, RoomByIdTransformer::class)->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Management
     *
     * Search Rooms
     *
     * Search for rooms using text query and advanced filters.
     */
    public function searchRooms(SearchRoomsRequest $request, SearchRoomsAction $action): JsonResponse
    {
        $searchParams = $request->only([
            'query',
            'min_price',
            'max_price',
            'capacity',
            'room_type_id',
            'check_in',
            'check_out',
        ]);

        $result = $action->run($searchParams);

        return Response::create([
            'rooms' => $result['rooms'],
            'metadata' => $result['metadata'],
        ], RoomTransformer::class, [
            'include' => ['roomType', 'images'],
        ])->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Management
     *
     * Check Room Availability
     *
     * Check if a room is available for the specified dates.
     */
    public function checkAvailability(CheckRoomAvailabilityRequest $request, CheckRoomAvailabilityAction $action): JsonResponse
    {
        $roomId = $request->id;
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        $isAvailable = $action->run($roomId, $checkIn, $checkOut);

        return Response::create([
            'room_id' => $roomId,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'is_available' => $isAvailable,
            'guests' => $request->get('guests', 1),
        ])->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Management
     *
     * Get Room Availability Calendar
     *
     * Get room availability calendar for date picker.
     */
    public function getAvailabilityCalendar(CheckRoomAvailabilityRequest $request, CheckRoomAvailabilityAction $action): JsonResponse
    {
        $roomId = $request->id;
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->addMonth()->format('Y-m-d'));

        $calendar = $action->getAvailabilityCalendar($roomId, $startDate, $endDate);

        return Response::create($calendar)->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Types
     *
     * Get Room Types
     *
     * Get available room types for filtering.
     */
    public function getRoomTypes(GetRoomTypesRequest $request, ListRoomTypesAction $action): JsonResponse
    {
        if ($request->get('include_room_count', false)) {
            $roomTypes = $action->getWithRoomCount();
        } else {
            $roomTypes = $action->run();
        }

        return Response::create($roomTypes, RoomTypeTransformer::class)->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Management
     *
     * Get Featured Rooms
     *
     * Get featured rooms for homepage display.
     */
    public function getFeaturedRooms(ListRoomsAction $action): JsonResponse
    {
        $featuredRooms = $action->getFeaturedRooms(6);

        return Response::create($featuredRooms, RoomTransformer::class, [
            'include' => ['roomType', 'images'],
        ])->ok();
    }

    /**
     * @group Rooms
     * @subgroup Room Management
     *
     * Get Search Suggestions
     *
     * Get room search suggestions for autocomplete.
     */
    public function getSearchSuggestions(SearchRoomsAction $action): JsonResponse
    {
        $query = request()->get('q', '');

        if (strlen($query) < 2) {
            return Response::create([])->ok();
        }

        $suggestions = $action->getSuggestions($query);

        return Response::create($suggestions)->ok();
    }

    /**
     * @subgroup Room Management
     *
     * Get Popular Filters
     *
     * Get popular search filters for UI.
     */
    public function getPopularFilters(SearchRoomsAction $action): JsonResponse
    {
        $filters = $action->getPopularFilters();

        return Response::create($filters)->ok();
    }

    /**
     * @subgroup Room Management
     *
     * Upload Room Images
     *
     * Upload multiple images for a specific room.
     * Supports up to 10 images per request.
     * Maximum file size: 10MB per image.
     * Supported formats: jpeg, png, jpg, gif, webp.
     */
    public function uploadImages(UploadRoomImageRequest $request, UploadRoomImageAction $action): JsonResponse
    {
        try {
            $result = $action->run($request->validated());

            return Response::create($result)->created();
        } catch (\Exception $e) {
            return Response::create([
                'message' => 'Failed to upload images',
                'error' => $e->getMessage()
            ])->badRequest();
        }
    }

    /**
     * @group System
     * @subgroup Health Check
     *
     * Check System Timeout
     *
     * Public API endpoint to check system timeout and performance.
     * No authentication required.
     *
     * @queryParam duration integer optional Timeout duration in seconds (1-300). Default: 30. Example: 10
     *
     * @response 200 {
     *   "success": true,
     *   "message": "System timeout check completed",
     *   "data": {
     *     "system_info": {
     *       "php_version": "8.2.0",
     *       "max_execution_time": "60",
     *       "memory_limit": "256M"
     *     },
     *     "tests": {
     *       "database_connection": {"status": "success", "execution_time_ms": 5.23},
     *       "sleep_test": {"status": "success", "requested_duration": 30}
     *     },
     *     "total_execution_time_ms": 30125.67
     *   }
     * }
     */
    public function checkTimeout(CheckTimeoutRequest $request, CheckTimeoutAction $action): JsonResponse
    {
        $data = $request->sanitize([
            'duration',
        ]);

        $result = $action->run($data);

        return response()->json([
            'success' => true,
            'message' => 'System timeout check completed',
            'data' => $result,
        ]);
    }
}
