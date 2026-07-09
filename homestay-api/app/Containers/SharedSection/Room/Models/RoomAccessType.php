<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class RoomAccessType extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'room_access_types';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];
    public array $translatable = ['name','description'];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    /**
     * Relationship: RoomAccessType has many rooms
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'access_type_id');
    }
}
