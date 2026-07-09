<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\CheckRoomAvailabilityTask;
use App\Ship\Parents\Actions\Action as ParentAction;

/**
 * Class CheckRoomAvailabilityAction
 * 
 * Client-specific action to check room availability for booking.
 * 
 * @package App\Containers\ClientSection\Room\Actions
 */
class CheckRoomAvailabilityAction extends ParentAction
{
    public function __construct(
        private readonly CheckRoomAvailabilityTask $checkRoomAvailabilityTask,
    ) {
    }

    /**
     * Check if room is available for booking
     */
    public function run(int $roomId, string $checkInDate, string $checkOutDate): bool
    {
        return $this->checkRoomAvailabilityTask->run($roomId, $checkInDate, $checkOutDate);
    }

    /**
     * Get room availability calendar for date picker
     */
    public function getAvailabilityCalendar(int $roomId, string $startDate, string $endDate): array
    {
        return $this->checkRoomAvailabilityTask->getAvailabilityCalendar($roomId, $startDate, $endDate);
    }
}
