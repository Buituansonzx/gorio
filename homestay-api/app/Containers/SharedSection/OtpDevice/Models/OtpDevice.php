<?php

namespace App\Containers\SharedSection\OtpDevice\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class OtpDevice extends ParentModel
{
    use HasUuids;

    CONST EXCEPT_VIETTEL_PHONE = '+84865941824';

    protected $guarded = [];

    protected $table = 'otp_devices';
}
