<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomAttribute;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomAttribute
 *
 * @extends ParentRepository<TModel>
 */
final class RoomAttributeRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'attribute_id' => '=',
        'value' => 'like',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomAttribute::class;
    }
}
