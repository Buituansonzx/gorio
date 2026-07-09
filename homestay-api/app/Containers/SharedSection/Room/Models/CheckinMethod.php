<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class CheckinMethod extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'checkin_methods';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    const CODE_SELF_CHECKIN = 'fully_self_checkin';
    const LIMIT_HOURS_BEFORE_CHECKIN = 2;
    const LIMIT_MINUTES_AFTER_CHECKOUT = 30;

    public array $translatable = ['name', 'description'];

    /**
     * Relationship: CheckinMethod can have many room checkin methods
     */
    public function roomCheckinInstruction()
    {
        return $this->hasMany(RoomCheckinInstruction::class, 'checkin_method_id');
    }
    public function rooms()
    {
        return $this->belongsMany(Room::class,'room_checkin_instruction', 'checkin_method_id', 'room_id');
    }
}
