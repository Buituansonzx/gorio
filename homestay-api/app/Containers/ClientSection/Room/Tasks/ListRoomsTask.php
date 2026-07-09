<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\SharedSection\Room\Tasks\ListRoomsTask as SharedListRoomsTask;
use App\Containers\ClientSection\Room\Data\Repositories\RoomRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ListRoomsTask
 *
 * Client-specific task to list rooms with client filters and optimizations.
 *
 * @package App\Containers\ClientSection\Room\Tasks
 */
class ListRoomsTask extends SharedListRoomsTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
        // Override parent constructor to use client repository
    }

    /**
     * List available rooms for clients
     */
    use \App\Ship\Traits\RoomPriceCalcTrait;

    public function run(array $filters = []): LengthAwarePaginator
    {
        $paginator = $this->repository->getAvailableRooms($filters);

        $checkInDate = !empty($filters['check_in_time']) ? Carbon::parse($filters['check_in_time']) : Carbon::now();
        $checkOutDate = !empty($filters['check_out_time']) ? Carbon::parse($filters['check_out_time']) : Carbon::now()->addDay();
        $maxGuests = !empty($filters['max_guests']) ? (int) $filters['max_guests'] : 1;
        $user = auth()->user();

        $maxDiscount = $this->getMaxGlobalVoucherDiscount($user);

        $paginator->getCollection()->transform(function ($room) use ($maxDiscount) {
            $room->applied_voucher_discount = $maxDiscount;
            return $room;
        });

        return $paginator;
    }

    /**
     * Search rooms with client-specific criteria
     */
    public function search(array $criteria): LengthAwarePaginator
    {
        return $this->repository->searchForClient($criteria);
    }

    /**
     * Get featured rooms for homepage
     */
    public function getFeaturedRooms(int $limit = 6)
    {
        return $this->repository->getAvailableRooms()
            ->take($limit)
            ->get();
    }
}
