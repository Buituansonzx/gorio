<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoomDiscountPricing extends ParentModel
{
    use HasUuids;
    protected $table = 'room_discount_pricing';
    protected $guarded = [];
    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    /**
     * Relationship: RoomDiscountPricing belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
