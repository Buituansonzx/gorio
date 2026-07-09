<?php

namespace App\Containers\MobileSection\Room\Tasks;

use App\Containers\MobileSection\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use App\Ship\Traits\RoomPriceCalcTrait;

final class ListRoomTask extends ParentTask
{
    use RoomPriceCalcTrait;

    public function __construct(
        private readonly RoomRepository $repository,
    ) {
        // Override parent constructor to use client repository
    }

    /**
     * List available rooms for clients
     */
    public function run(array $filters = [])
    {
        $paginator = $this->repository->getAvailableRooms($filters);

        $user = auth()->user();
        $maxDiscount = $this->getMaxGlobalVoucherDiscount($user);

        $paginator->getCollection()->transform(function ($room) use ($maxDiscount) {
            $room->applied_voucher_discount = $maxDiscount;
            return $room;
        });

        return $paginator;
    }
}
