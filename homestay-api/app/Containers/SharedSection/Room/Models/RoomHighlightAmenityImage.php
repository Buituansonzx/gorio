<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;

final class RoomHighlightAmenityImage extends ParentModel
{
    use HasUuids;
    protected $table = 'room_highlight_amenities_images';

    protected $guarded = [];

    protected $casts = [
        'is_cover' => 'boolean',
        'order_index' => 'integer',
        'variants' => 'array'
    ];

    /**
     * Relationship: RoomHighlightAmenityImage belongs to a room highlight amenity
     */
    public function roomHighlightAmenity()
    {
        return $this->belongsTo(RoomHighlightAmenity::class, 'id');
    }
    public function url(?string $path = null): string
    {
        return Storage::disk($this->disk)->url($path ?? $this->path);
    }
}
