<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;

final class RoomCodeMapping extends ParentModel
{

    protected $table = 'room_code_mappings';

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'room_id';

    public function rooms()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
