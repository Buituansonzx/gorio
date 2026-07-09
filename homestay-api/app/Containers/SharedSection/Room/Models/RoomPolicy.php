<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoomPolicy extends ParentModel
{
    use HasUuids;
    protected $table = 'room_policy';

    protected $guarded = [];

    public $timestamps = false;

    const POLICY_CANCELLATION = 'cancellation';

    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }
}
