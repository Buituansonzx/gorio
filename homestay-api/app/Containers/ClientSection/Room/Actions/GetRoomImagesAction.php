<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\SharedSection\Room\Actions\GetRoomImagesAction as SharedGetRoomImagesAction;
use App\Containers\ClientSection\Room\Tasks\GetRoomImagesTask;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class GetRoomImagesAction
 * 
 * Client-specific action to get room images optimized for client display.
 * 
 * @package App\Containers\ClientSection\Room\Actions
 */
class GetRoomImagesAction extends SharedGetRoomImagesAction
{
    public function __construct(
        private readonly GetRoomImagesTask $getRoomImagesTask,
    ) {
        // Override parent constructor
    }

    /**
     * Get optimized room images for client display
     */
    public function run(int $roomId): Collection
    {
        return $this->getRoomImagesTask->run($roomId);
    }

    /**
     * Get primary image for room preview cards
     */
    public function getPrimaryImage(int $roomId)
    {
        return $this->getRoomImagesTask->getPrimaryImage($roomId);
    }
}
