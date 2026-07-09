<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoomIcalFile extends ParentModel
{
    use HasUuids;

    protected $table = 'room_ical_files';

    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

}
