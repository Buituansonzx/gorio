<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomDiscountPoliciesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomDiscountPoliciesAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomDiscountPoliciesTask $getRoomDiscountPoliciesTask,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->getRoomDiscountPoliciesTask->run($roomId);
    }
}
