<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class RoomImageGroup extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'room_image_area_group';

    protected $guarded = [];

    CONST CODE_LIVING_ROOM = 'living_room';

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public array $translatable = ['name'];

    /**
     * Relationship: RoomImageGroup belongs to a room
     */

    public function images()
    {
        return $this->hasMany(RoomImage::class, 'group_id');
    }
}
