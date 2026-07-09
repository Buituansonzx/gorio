<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\ListRoomsTask;
use App\Containers\ClientSection\Room\Tasks\ListRoomTypesTask;
use App\Containers\ClientSection\Room\Tasks\GetRoomImagesTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SearchRoomsAction
 * 
 * Comprehensive room search action for client interface.
 * 
 * @package App\Containers\ClientSection\Room\Actions
 */
class SearchRoomsAction extends ParentAction
{
    public function __construct(
        private readonly ListRoomsTask $listRoomsTask,
        private readonly ListRoomTypesTask $listRoomTypesTask,
        private readonly GetRoomImagesTask $getRoomImagesTask,
    ) {
    }

    /**
     * Search rooms with comprehensive filters and sorting
     */
    public function run(array $searchParams): array
    {
        // Extract search parameters
        $filters = [
            'min_price' => $searchParams['min_price'] ?? null,
            'max_price' => $searchParams['max_price'] ?? null,
            'capacity' => $searchParams['capacity'] ?? null,
            'room_type_id' => $searchParams['room_type_id'] ?? null,
            'check_in' => $searchParams['check_in'] ?? null,
            'check_out' => $searchParams['check_out'] ?? null,
        ];

        // Search criteria for text-based search
        $criteria = [];
        if (!empty($searchParams['query'])) {
            $criteria['name'] = $searchParams['query'];
            $criteria['description'] = $searchParams['query'];
        }

        // Get rooms based on search type
        if (!empty($criteria)) {
            $rooms = $this->listRoomsTask->search($criteria);
        } else {
            $rooms = $this->listRoomsTask->run(array_filter($filters));
        }

        // Get available room types for filtering
        $roomTypes = $this->listRoomTypesTask->getWithRoomCount();

        // Build search result metadata
        $metadata = [
            'total_rooms' => $rooms->total(),
            'current_page' => $rooms->currentPage(),
            'per_page' => $rooms->perPage(),
            'last_page' => $rooms->lastPage(),
            'filters_applied' => array_filter($filters),
            'available_room_types' => $roomTypes,
        ];

        return [
            'rooms' => $rooms,
            'metadata' => $metadata,
        ];
    }

    /**
     * Get search suggestions based on query
     */
    public function getSuggestions(string $query): array
    {
        // This could be enhanced with ElasticSearch or similar
        $rooms = $this->listRoomsTask->search(['name' => $query]);
        
        return $rooms->take(5)->map(function ($room) {
            return [
                'id' => $room->id,
                'name' => $room->name,
                'type' => $room->roomType->name,
                'price' => $room->formatted_price,
            ];
        })->toArray();
    }

    /**
     * Get popular search filters based on existing data
     */
    public function getPopularFilters(): array
    {
        return [
            'price_ranges' => [
                ['min' => 0, 'max' => 500000, 'label' => 'Dưới 500k'],
                ['min' => 500000, 'max' => 1000000, 'label' => '500k - 1tr'],
                ['min' => 1000000, 'max' => 2000000, 'label' => '1tr - 2tr'],
                ['min' => 2000000, 'max' => null, 'label' => 'Trên 2tr'],
            ],
            'capacity_options' => [1, 2, 3, 4, 5, 6],
            'room_types' => $this->listRoomTypesTask->getWithRoomCount(),
        ];
    }
}
