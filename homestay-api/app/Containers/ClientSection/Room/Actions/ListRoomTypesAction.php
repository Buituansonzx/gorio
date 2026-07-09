<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\SharedSection\Room\Actions\ListRoomTypesAction as SharedListRoomTypesAction;
use App\Containers\ClientSection\Room\Tasks\ListRoomTypesTask;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ListRoomTypesAction
 * 
 * Client-specific action to list room types for client selection.
 * 
 * @package App\Containers\ClientSection\Room\Actions
 */
class ListRoomTypesAction extends SharedListRoomTypesAction
{
    public function __construct(
        private readonly ListRoomTypesTask $listRoomTypesTask,
    ) {
        // Override parent constructor
    }

    /**
     * Get active room types for client
     */
    public function run(): Collection
    {
        return $this->listRoomTypesTask->run();
    }

    /**
     * Get room types with available room count for filtering
     */
    public function getWithRoomCount(): Collection
    {
        return $this->listRoomTypesTask->getWithRoomCount();
    }
}
