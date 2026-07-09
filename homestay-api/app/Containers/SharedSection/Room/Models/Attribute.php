<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Attribute extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'attributes';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public array $translatable = ['name'];

    const CODE_BEDROOM = 'bedroom';
    const CODE_BED = 'bed';
    const CODE_BATHROOM = 'private_bathroom';

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    /**
     * Relationship: Attribute has many room attributes
     */
    public function roomAttributes(): HasMany
    {
        return $this->hasMany(RoomAttribute::class, 'attribute_id');
    }

    public function room()
    {
        return $this->belongsToMany(Room::class, 'room_attributes', 'attribute_id', 'room_id');
    }
}
