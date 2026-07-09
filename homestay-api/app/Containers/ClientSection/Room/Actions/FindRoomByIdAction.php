<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\UI\API\Requests\FindRoomByIdRequest;
use App\Containers\ClientSection\Room\Tasks\FindRoomByIdTask;
use App\Containers\SharedSection\Room\Models\Room;

/**
 * Class FindRoomByIdAction
 *
 * Client-specific action to find room by ID with client-optimized data.
 *
 * @package App\Containers\ClientSection\Room\Actions
 */
class FindRoomByIdAction
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
