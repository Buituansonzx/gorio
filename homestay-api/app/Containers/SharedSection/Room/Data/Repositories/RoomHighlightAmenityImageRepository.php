<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomHighlightAmenityImage;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomHighlightAmenityImage
 *
 * @extends ParentRepository<TModel>
 */
final class RoomHighlightAmenityImageRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
