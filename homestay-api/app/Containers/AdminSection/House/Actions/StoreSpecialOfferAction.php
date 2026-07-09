<?php

namespace App\Containers\AdminSection\House\Actions;

use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Parents\Actions\Action as ParentAction;

final class StoreSpecialOfferAction extends ParentAction
{
    public function run($data, $houseId)
    {
        $house = House::findOrFail($houseId);
        $rooms = $house->rooms;
        foreach ($rooms as $room) {
            $room->specialOffers()->updateOrCreate(
                ['room_id' => $room->id],
                $data
            );
        }
        return $house;
    }
}
