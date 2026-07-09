<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomSpecialOffer extends ParentModel
{
    use HasUuids;
    protected $table = 'room_special_offers';

    protected $fillable = [
        'room_id',
        'last_minute_hours',
        'last_minute_discount_percent',
        'monthly_price',
        'currency',
    ];

    protected $casts = [
        'last_minute_hours' => 'integer',
        'last_minute_discount_percent' => 'decimal:2',
        'monthly_price' => 'decimal:2',
    ];

    /**
     * Relationship: RoomSpecialOffer belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
