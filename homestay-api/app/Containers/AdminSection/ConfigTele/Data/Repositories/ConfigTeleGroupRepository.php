<?php

namespace App\Containers\AdminSection\ConfigTele\Data\Repositories;

use App\Ship\Parents\Repositories\Repository as ParentRepository;
use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;

class ConfigTeleGroupRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
        'chat_id' => 'like',
        'is_active' => '=',
    ];

    public function model(): string
    {
        return ConfigTeleGroup::class;
    }
}
