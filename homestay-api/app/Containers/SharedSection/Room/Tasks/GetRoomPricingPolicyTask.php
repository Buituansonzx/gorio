<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomPricingPolicyRepository;
use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomPricingPolicyTask extends ParentTask
{
    public function __construct(
        private readonly RoomPricingPolicyRepository $repository,
    ) {
    }

    public function run(int $roomId): ?RoomPricingPolicy
    {
        return $this->repository->findWhere(['room_id' => $roomId])->first();
    }
}
