<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomTimePricingAction extends ParentAction
{
    public function run(string $roomId): array
    {
        $room = Room::with([
            'fixedCheckTime',
            'comboPricing',
            'hourlyPricing'
        ])->findOrFail($roomId);
        return [
            'fixed_check_times' => $room->fixedCheckTime,
            'combo_pricing' => $room->comboPricing,
            'hourly_pricing' => $room->hourlyPricing
        ];
    }
}
