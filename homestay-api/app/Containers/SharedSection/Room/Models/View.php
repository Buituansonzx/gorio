<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class View extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'views';

    protected $guarded = [];

    public array $translatable = ['name', 'description'];


    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_views', 'view_id', 'room_id');
    }
}
