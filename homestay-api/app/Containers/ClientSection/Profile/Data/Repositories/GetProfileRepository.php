<?php

namespace App\Containers\ClientSection\Profile\Data\Repositories;

use App\Containers\ClientSection\Profile\Models\User;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of User
 *
 * @extends ParentRepository<TModel>
 */
final class GetProfileRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
