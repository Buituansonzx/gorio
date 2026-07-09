<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class RoomPricingPolicy extends ParentModel
{
    use HasUuids;
    protected $table = 'room_pricing_policies';

    protected $fillable = [
        'room_id',
        'checkin_time',
        'checkout_time',
        'base_price',
        'currency',
        'max_guests',
        'standard_guests',
        'extra_adult_price',
        'extra_child_price',
        'extra_hour_price',
        'cleaning_fee',
        'deposit_amount',
        'cleaning_gap_hours',
    ];

    protected $casts = [
        'checkin_time' => 'datetime:H:i',
        'checkout_time' => 'datetime:H:i',
        'base_price' => 'decimal:2',
        'extra_adult_price' => 'decimal:2',
        'extra_child_price' => 'decimal:2',
        'extra_hour_price' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'max_guests' => 'integer',
        'standard_guests' => 'integer',
        'cleaning_gap_hours' => 'integer',
    ];

    /**
     * Relationship: RoomPricingPolicy belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relationship: RoomPricingPolicy has many weekday prices
     */
    public function weekdayPrices(): HasMany
    {
        return $this->hasMany(RoomWeekdayPrice::class, 'policy_id');
    }
}
