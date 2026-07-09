<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomHighlightFacilityImage extends ParentModel
{
    use HasUuids;
    protected $table = 'room_highlight_facility_images';

    protected $fillable = [
        'facility_id',
        'image_url',
        's3_key',
        'is_cover',
        'order_index',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Relationship: RoomHighlightFacilityImage belongs to a room highlight facility
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(RoomHighlightFacility::class, 'facility_id');
    }
}
