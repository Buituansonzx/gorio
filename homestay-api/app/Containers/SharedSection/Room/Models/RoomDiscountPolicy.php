<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomDiscountPolicy extends ParentModel
{
    use HasUuids;
    protected $table = 'room_discount_policies';

    protected $fillable = [
        'room_id',
        'dayuse_checkin',
        'dayuse_checkout',
        'price_ratio_id',
        'late_checkin',
        'late_checkout',
        'late_discount',
    ];

    protected $casts = [
        'dayuse_checkin' => 'datetime:H:i',
        'dayuse_checkout' => 'datetime:H:i',
        'late_checkin' => 'datetime:H:i',
        'late_checkout' => 'datetime:H:i',
    ];

    /**
     * Relationship: RoomDiscountPolicy belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relationship: RoomDiscountPolicy belongs to a price ratio
     */
    public function priceRatio(): BelongsTo
    {
        return $this->belongsTo(PriceRatio::class, 'price_ratio_id');
    }
}
