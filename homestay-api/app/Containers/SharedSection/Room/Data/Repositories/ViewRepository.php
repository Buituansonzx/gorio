<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\View;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of View
 *
 * @extends ParentRepository<TModel>
 */
final class ViewRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
