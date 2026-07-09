<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomSurroundingFacility extends ParentModel
{
//    use HasUuids;
    protected $table = 'room_surrounding_facilities';
    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'facility_id',
        'distance',
    ];

    /**
     * Relationship: RoomSurroundingFacility belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relationship: RoomSurroundingFacility belongs to a surrounding facility
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(SurroundingFacility::class, 'facility_id');
    }
}
