<?php

namespace App\Containers\MobileSection\Room\Actions;

use App\Containers\MobileSection\Room\Tasks\FindRoomByIdTask;
use App\Containers\MobileSection\Room\UI\API\Requests\FindRoomByIdRequest;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindRoomByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindRoomByIdTask $findRoomByIdTask,
    ) {
        // Override parent constructor to use client-specific task
    }

    /**
     * Find room by ID for client viewing
     */
    public function run(FindRoomByIdRequest $request): Room
    {
        $roomId = $request->id;
        return $this->findRoomByIdTask->run($request->validated(), $roomId);
    }

    /**
     * Find room for booking process with availability check
     */
    public function runForBooking(string $id): Room
    {
        return $this->findRoomByIdTask->runForBooking($id);
    }
}
