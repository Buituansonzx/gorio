<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Ota extends ParentModel
{
    use HasUuids;

    protected $table = 'otas';

    protected $guarded = [];

    CONST CODE_HONG_MANAGE = 'HONG_MANAGE';


}
