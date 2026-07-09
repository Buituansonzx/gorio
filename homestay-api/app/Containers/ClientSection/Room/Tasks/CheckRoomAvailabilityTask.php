<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\ClientSection\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

/**
 * Class CheckRoomAvailabilityTask
 * 
 * Client-specific task to check room availability for booking.
 * 
 * @package App\Containers\ClientSection\Room\Tasks
 */
class CheckRoomAvailabilityTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    /**
     * Check if room is available for given dates
     */
    public function run(int $roomId, string $checkInDate, string $checkOutDate): bool
    {
        $room = $this->repository->find($roomId);
        
        if (!$room || !$room->isInstantBookable()) {
            return false;
        }

        // Here you would add logic to check against bookings
        // For now, just return true if room exists and is bookable
        // TODO: Implement booking conflict checking
        
        return true;
    }

    /**
     * Get room availability calendar
     */
    public function getAvailabilityCalendar(int $roomId, string $startDate, string $endDate): array
    {
        // TODO: Implement calendar availability logic
        // This would return an array of available/unavailable dates
        
        return [
            'room_id' => $roomId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'available_dates' => [],
            'unavailable_dates' => [],
        ];
    }
}
