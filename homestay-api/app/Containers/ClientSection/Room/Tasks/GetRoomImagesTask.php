<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\SharedSection\Room\Tasks\GetRoomImagesTask as SharedGetRoomImagesTask;
use App\Containers\ClientSection\Room\Data\Repositories\RoomImageRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class GetRoomImagesTask
 * 
 * Client-specific task to get room images optimized for client display.
 * 
 * @package App\Containers\ClientSection\Room\Tasks
 */
class GetRoomImagesTask extends SharedGetRoomImagesTask
{
    public function __construct(
        private readonly RoomImageRepository $repository,
    ) {
        // Override parent constructor
    }

    /**
     * Get optimized room images for client display
     */
    public function run(int $roomId): Collection
    {
        return $this->repository->getClientImages($roomId);
    }

    /**
     * Get primary image for room preview
     */
    public function getPrimaryImage(int $roomId)
    {
        return $this->repository->getPrimaryImage($roomId);
    }
}
