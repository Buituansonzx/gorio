<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomType;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomType
 *
 * @extends ParentRepository<TModel>
 */
class RoomTypeRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'code' => '=',
        'name' => 'like',
        'description' => 'like',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomType::class;
    }
}
