<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomDiscountPolicy;
use App\Containers\SharedSection\Room\Tasks\CreateRoomDiscountPolicyTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomDiscountPolicyAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomDiscountPolicyTask $createRoomDiscountPolicyTask,
    ) {
    }

    public function run(array $data): RoomDiscountPolicy
    {
        return $this->createRoomDiscountPolicyTask->run($data);
    }
}
