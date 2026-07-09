<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportContact extends ParentModel
{
    use SoftDeletes, HasUuids;
    protected $table = 'support_contacts';

    protected $fillable = [
        'label',
        'value',
    ];

    public const LABEL_ZALO = 'zalo';
    public const LABEL_FACEBOOK = 'facebook';
    public const LABEL_HOTLINE = 'hotline';
    public const LABEL_EMAIL = 'email';
}
