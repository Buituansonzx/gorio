<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class RoomHighlightFacility extends ParentModel
{
    use HasUuids;
    protected $table = 'room_highlight_facilities';

    protected $fillable = [
        'room_id',
        'code',
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    /**
     * Relationship: RoomHighlightFacility belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relationship: RoomHighlightFacility has many images
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomHighlightFacilityImage::class, 'facility_id');
    }
}
