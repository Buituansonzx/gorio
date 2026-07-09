<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomAttribute extends ParentModel
{
//    use HasUuids;

    protected $table = 'room_attributes';
    public $timestamps = false;
    protected $fillable = [
        'room_id',
        'attribute_id',
        'quantity',
    ];

    /**
     * Relationship: RoomAttribute belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relationship: RoomAttribute belongs to an attribute
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
