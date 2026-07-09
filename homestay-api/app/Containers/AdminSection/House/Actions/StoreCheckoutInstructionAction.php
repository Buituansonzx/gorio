<?php

namespace App\Containers\AdminSection\House\Actions;

use App\Containers\SharedSection\Room\Models\CheckoutInstructionType;
use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Parents\Actions\Action as ParentAction;

final class StoreCheckoutInstructionAction extends ParentAction
{
    public function run($houseId)
    {
        $house = House::findOrFail($houseId);
        $rooms = $house->rooms;

        $checkoutInstructionCodes = [
            'collect_used_towels',
            'custom_request',
            'lock_doors',
            'return_keys',
            'take_out_trash',
            'turn_off_devices',
        ];

        // Load instruction types
        $instructionTypes = CheckoutInstructionType::whereIn('code', $checkoutInstructionCodes)
            ->get()
            ->keyBy('code');

        if ($instructionTypes->isEmpty()) {
            return $house;
        }

        // Prepare sync data
        $syncData = [];
        foreach ($checkoutInstructionCodes as $code) {
            if (!isset($instructionTypes[$code])) {
                continue;
            }

            if ($code === 'custom_request') {
                $syncData[$instructionTypes[$code]->id] = [
                    'content' => 'Dọn dẹp bát đũa sau khi sử dụng',
                ];
            } else {
                $syncData[$instructionTypes[$code]->id] = [
                    'content' => null,
                ];
            }
        }

        if (empty($syncData)) {
            return $house;
        }

        foreach ($rooms as $room) {
            if ($room->checkoutInstructionType()->count() === 0) {
                $room->checkoutInstructionType()->attach($syncData);
            }
        }

        return $house;
    }
}
