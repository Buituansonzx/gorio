<?php

namespace App\Containers\SharedSection\AppVersion\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppVersion extends ParentModel
{
    use HasUuids, SoftDeletes;

    protected $table = 'app_versions';

    protected $fillable = [
        'platform',
        'version_name',
        'version_code',
        'is_force_update',
        'update_url',
        'title',
        'content',
        'status',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'is_force_update' => 'boolean',
        'status' => 'integer',
        'version_code' => 'integer',
    ];
}
