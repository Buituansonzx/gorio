<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoomHighlightAmenity extends ParentModel
{
    use HasUuids;
    protected $table = 'room_highlight_amenities';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Relationship: RoomHighlightAmenity belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function images()
    {
        return $this->hasMany(RoomHighlightAmenityImage::class, 'highlight_amenity_id');
    }

}
