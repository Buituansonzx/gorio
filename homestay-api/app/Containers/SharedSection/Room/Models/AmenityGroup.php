<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class AmenityGroup extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'amenity_group';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public array $translatable = ['name'];

    /**
     * Relationship: AmenityGroup has many amenities
     */
    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'amenity_group_id');
    }
}
