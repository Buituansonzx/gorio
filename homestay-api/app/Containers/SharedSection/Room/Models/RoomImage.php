<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomImage extends ParentModel
{
    use HasUuids;

    protected $table = 'room_images';

    protected $guarded = [];

    protected $casts = [
        'is_cover' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Relationship: RoomImage belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    /**
     * Relationship: RoomImage belongs to a room image group
     */
    public function imageAreaGroup(): BelongsTo
    {
        return $this->belongsTo(RoomImageGroup::class, 'room_image_area_group_id');
    }
}
