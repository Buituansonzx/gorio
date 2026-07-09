<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class HouseRule extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'house_rules';

    CONST ID_ADD_RULES = '01996a4d-7f47-7263-9393-c69fbc574777';

    protected $fillable = [
        'code',
        'name',
        'type',
        'is_required',
    ];

    public $timestamps = false;

    public array $translatable = ['name'];

    public function roomHouseRules()
    {
        return $this->hasMany(RoomHouseRule::class, 'house_rule_id');
    }
}
