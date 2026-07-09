<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class SurroundingFacility extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'surrounding_facilities';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public array $translatable = ['name', 'description'];
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    /**
     * Relationship: SurroundingFacility has many room surrounding facilities
     */
    public function roomSurroundingFacilities(): HasMany
    {
        return $this->hasMany(RoomSurroundingFacility::class, 'facility_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_surrounding_facilities', 'facility_id', 'room_id');
    }
}
