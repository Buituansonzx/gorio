<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomDiscountPolicyRepository;
use App\Containers\SharedSection\Room\Models\RoomDiscountPolicy;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomDiscountPolicyTask extends ParentTask
{
    public function __construct(
        private readonly RoomDiscountPolicyRepository $repository,
    ) {
    }

    public function run(array $data): RoomDiscountPolicy
    {
        return $this->repository->create($data);
    }
}
