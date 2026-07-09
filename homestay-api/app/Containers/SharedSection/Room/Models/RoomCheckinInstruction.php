<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class RoomCheckinInstruction extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'room_checkin_instruction';

    protected $guarded = [];

    public array $translatable = [];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function checkinMethod()
    {
        return $this->belongsTo(CheckinMethod::class);
    }
}
