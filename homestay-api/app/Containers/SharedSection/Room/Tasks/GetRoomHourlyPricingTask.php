<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomHourlyPricingRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomHourlyPricingTask extends ParentTask
{
    public function __construct(
        private readonly RoomHourlyPricingRepository $repository,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->repository->findWhere(['room_id' => $roomId]);
    }
}
