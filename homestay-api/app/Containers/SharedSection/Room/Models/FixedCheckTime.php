<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class FixedCheckTime extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'fixed_check_times';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public array $translatable = ['name', 'description'];

    CONST CODE_STD = 'std';
    CONST CODE_DAY_TIME = 'day_time';
    public function room()
    {
        return $this->belongsToMany(Room::class, 'room_fixed_check_time')->withPivot('price', 'mon_price', 'tue_price', 'wed_price', 'thu_price', 'fri_price', 'sat_price', 'sun_price', 'mon_buffer_price', 'tue_buffer_price', 'wed_buffer_price', 'thu_buffer_price', 'fri_buffer_price', 'sat_buffer_price', 'sun_buffer_price');

    }

}
