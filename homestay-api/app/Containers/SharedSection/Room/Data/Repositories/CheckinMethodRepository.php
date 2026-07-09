<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of CheckinMethod
 *
 * @extends ParentRepository<TModel>
 */
final class CheckinMethodRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
