<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

final class RoomLock extends ParentModel
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $table = 'room_locks';

    protected $casts = [
        'id' => 'string',
        'room_id' => 'string',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    const ADMIN_LOCK = 'admin_lock';
    const HOST_LOCK  = 'host_lock';
    const DIRTY_ROOM = 'dirty_room';
    const EXTERNAL_ORDER = 'external_order';

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
