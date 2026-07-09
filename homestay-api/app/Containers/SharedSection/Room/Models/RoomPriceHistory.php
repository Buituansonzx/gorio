<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomPriceHistory extends ParentModel
{
    use HasUuids;
    protected $table = 'room_price_history';

    protected $fillable = [
        'room_id',
        'policy_snapshot',
        'changed_at',
    ];

    protected $casts = [
        'policy_snapshot' => 'array',
        'changed_at' => 'datetime',
    ];

    /**
     * Relationship: RoomPriceHistory belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
