<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\SharedSection\Room\Tasks\ListRoomTypesTask as SharedListRoomTypesTask;
use App\Containers\ClientSection\Room\Data\Repositories\RoomTypeRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ListRoomTypesTask
 * 
 * Client-specific task to list room types for client selection.
 * 
 * @package App\Containers\ClientSection\Room\Tasks
 */
class ListRoomTypesTask extends SharedListRoomTypesTask
{
    public function __construct(
        private readonly RoomTypeRepository $repository,
    ) {
        // Override parent constructor
    }

    /**
     * Get active room types for client
     */
    public function run(): Collection
    {
        return $this->repository->getActiveForClient();
    }

    /**
     * Get room types with available room count
     */
    public function getWithRoomCount(): Collection
    {
        return $this->repository->getWithRoomCount();
    }
}
