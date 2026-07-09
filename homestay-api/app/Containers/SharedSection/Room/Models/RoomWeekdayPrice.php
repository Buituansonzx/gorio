<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomWeekdayPrice extends ParentModel
{
    use HasUuids;
    protected $table = 'room_weekday_prices';

    protected $fillable = [
        'policy_id',
        'weekday',
        'price',
    ];

    protected $casts = [
        'weekday' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Relationship: RoomWeekdayPrice belongs to a pricing policy
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(RoomPricingPolicy::class, 'policy_id');
    }
}
