<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class Amenity extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'amenities';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    const CODE_PROJECTOR = 'projector';
    const CODE_BATHTUB = 'bathtub';
    const CODE_BALCONY = 'patio_or_balcony';
    const CODE_DUPLEX = 'duplex';

    public array $translatable = ['name'];

    /**
     * Relationship: Amenity can have many room amenities
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_amenities');
    }

    public function highlightAmenities()
    {
        return $this->hasMany(RoomHighlightAmenity::class, 'amenity_id');
    }

    /**
     * Relationship: Amenity belongs to a group
     */
    public function group()
    {
        return $this->belongsTo(AmenityGroup::class, 'amenity_group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
