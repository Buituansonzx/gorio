<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoomIcalFileConfig extends ParentModel
{
    use HasUuids;

    protected $table = 'room_ical_file_config';

    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function ota()
    {
        return $this->belongsTo(Ota::class, 'ota_id');
    }
}
