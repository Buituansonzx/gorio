<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoomParkingRule extends ParentModel
{
    use HasUuids;
    protected $table = 'room_parking_rules';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Relationship: RoomParkingRule belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
