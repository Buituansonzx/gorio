<?php

namespace App\Containers\SharedSection\Order\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Sms extends ParentModel
{
    use HasUuids;

    protected $table = 'sms';

    protected $guarded = [];



}
