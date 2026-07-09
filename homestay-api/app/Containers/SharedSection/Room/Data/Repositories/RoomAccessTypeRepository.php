<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomAccessType;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomAccessType
 *
 * @extends ParentRepository<TModel>
 */
final class RoomAccessTypeRepository extends ParentRepository
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
        return RoomAccessType::class;
    }
}
