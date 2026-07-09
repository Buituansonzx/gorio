<?php

namespace App\Containers\MobileSection\Room\Tasks;

use App\Containers\MobileSection\Room\Data\Repositories\RoomRepository;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindRoomByIdTask extends ParentTask
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
