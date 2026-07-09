<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Setting extends ParentModel
{
    protected $table = 'setting';

    protected $fillable = [
        'key',
        'value',
    ];

    CONST DEVELOPER_EMAILS = 'developer_email';
}
