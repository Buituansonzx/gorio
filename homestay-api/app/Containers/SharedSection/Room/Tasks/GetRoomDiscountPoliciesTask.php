<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomDiscountPolicyRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomDiscountPoliciesTask extends ParentTask
{
    public function __construct(
        private readonly RoomDiscountPolicyRepository $repository,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->repository->findWhere(['room_id' => $roomId]);
    }
}
