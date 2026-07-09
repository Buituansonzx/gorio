<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomAttributeRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomAttributesTask extends ParentTask
{
    public function __construct(
        private readonly RoomAttributeRepository $repository,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->repository->findWhere(['room_id' => $roomId]);
    }
}
