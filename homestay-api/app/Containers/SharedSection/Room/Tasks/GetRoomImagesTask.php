<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomImageRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\Collection;

final class GetRoomImagesTask extends ParentTask
{
    public function __construct(
        private readonly RoomImageRepository $repository,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->repository->findWhere(['room_id' => $roomId]);
    }
}
