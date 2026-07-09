<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomHighlightAmenity;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomHighlightAmenity
 *
 * @extends ParentRepository<TModel>
 */
final class RoomHighlightAmenityRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
