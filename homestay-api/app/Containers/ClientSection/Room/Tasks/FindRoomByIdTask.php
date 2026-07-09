<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\ClientSection\Room\Data\Repositories\RoomRepository;

/**
 * Class FindRoomByIdTask
 *
 * Client-specific task to find room by ID with client-focused data.
 *
 * @package App\Containers\ClientSection\Room\Tasks
 */
class FindRoomByIdTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
        // Note: We override the parent constructor to use our client repository
    }

    /**
     * Find room by ID with client-specific relationships loaded
     */
    public function run($request,string $id): Room
    {
        return $this->repository->getClientRoomDetails($request,$id);
    }

    /**
     * Find room for booking process
     */
    public function runForBooking(string $id): Room
    {
        $room = $this->repository->getClientRoomDetails($id);

        if (!$room->isInstantBookable()) {
            throw new \Exception('Room is not available for instant booking');
        }

        return $room;
    }
}
