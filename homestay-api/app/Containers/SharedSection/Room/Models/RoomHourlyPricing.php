<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomHourlyPricing extends ParentModel
{
    use HasUuids;
    protected $table = 'room_hourly_pricing';

    protected $guarded = [];

    protected $casts = [
        'min_hours' => 'integer',
        'min_hours_price' => 'decimal:2',
        'next_hour_price' => 'decimal:2',
    ];

    /**
     * Relationship: RoomHourlyPricing belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
