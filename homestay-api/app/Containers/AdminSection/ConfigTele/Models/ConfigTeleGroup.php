<?php

namespace App\Containers\AdminSection\ConfigTele\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use App\Containers\SharedSection\Room\Models\House;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigTeleGroup extends ParentModel
{
    use HasUuids, SoftDeletes;
    protected $table = 'config_tele_groups';

    protected $guarded = [];

    public function houses()
    {
        return $this->belongsToMany(
            House::class,
            'config_tele_group_house',
            'config_tele_group_id',
            'house_id'
        );
    }
}
