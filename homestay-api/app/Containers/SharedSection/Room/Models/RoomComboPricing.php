<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomComboPricing extends ParentModel
{
    use HasUuids;
    protected $table = 'room_combo_pricing';

    protected $fillable = [
        'room_id',
        'start_time',
        'end_time',
        'price',
        'buffer_price',
        'mon_buffer_price',
        'tue_buffer_price',
        'wed_buffer_price',
        'thu_buffer_price',
        'fri_buffer_price',
        'sat_buffer_price',
        'sun_buffer_price',
        'mon_price',
        'tue_price',
        'wed_price',
        'thu_price',
        'fri_price',
        'sat_price',
        'sun_price',
        'currency',
    ];

    protected $casts = [
//        'start_time' => 'datetime:H:i',
//        'end_time' => 'datetime:H:i',
        'mon_price' => 'decimal:2',
        'tue_price' => 'decimal:2',
        'wed_price' => 'decimal:2',
        'thu_price' => 'decimal:2',
        'fri_price' => 'decimal:2',
        'sat_price' => 'decimal:2',
        'sun_price' => 'decimal:2',
    ];

    /**
     * Relationship: RoomComboPricing belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
