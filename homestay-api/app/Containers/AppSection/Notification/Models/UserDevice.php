<?php

namespace App\Containers\AppSection\Notification\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class UserDevice extends ParentModel
{
    use HasUuids;

    protected $table = 'user_devices';

    protected $guarded = [];
}
