<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomPricingPolicyRepository;
use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomPricingPolicyTask extends ParentTask
{
    public function __construct(
        private readonly RoomPricingPolicyRepository $repository,
    ) {
    }

    public function run(array $data): RoomPricingPolicy
    {
        return $this->repository->create($data);
    }
}
