<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\SharedSection\Room\Actions\ListRoomsAction as SharedListRoomsAction;
use App\Containers\ClientSection\Room\Tasks\ListRoomsTask;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ListRoomsAction
 *
 * Client-specific action to list rooms with client filters and search.
 *
 * @package App\Containers\ClientSection\Room\Actions
 */
class ListRoomsAction extends SharedListRoomsAction
{
    public function __construct(
        private readonly ListRoomsTask $listRoomsTask,
    ) {
        // Override parent constructor
    }

    /**
     * List available rooms for clients with filters
     */
    public function run(array $filters = []): LengthAwarePaginator
    {
        return $this->listRoomsTask->run($filters);
    }

    /**
     * Search rooms with client criteria
     */
    public function search(array $criteria): LengthAwarePaginator
    {
        return $this->listRoomsTask->search($criteria);
    }

    /**
     * Get featured rooms for homepage
     */
    public function getFeaturedRooms(int $limit = 6): Collection
    {
        return $this->listRoomsTask->getFeaturedRooms($limit);
    }
}
