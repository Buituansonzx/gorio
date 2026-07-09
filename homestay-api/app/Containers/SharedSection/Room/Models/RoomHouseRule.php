<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;

final class RoomHouseRule extends ParentModel
{
    protected $table = 'room_house_rule';

    protected $guarded = [];

    protected $casts = [
        'room_id' => 'string',
        'house_rule_id' => 'string',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function houseRule()
    {
        return $this->belongsTo(HouseRule::class, 'house_rule_id');
    }
}
