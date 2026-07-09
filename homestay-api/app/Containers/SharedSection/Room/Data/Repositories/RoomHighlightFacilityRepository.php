<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomHighlightFacility;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomHighlightFacility
 *
 * @extends ParentRepository<TModel>
 */
final class RoomHighlightFacilityRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'code' => '=',
        'name' => 'like',
        'description' => 'like',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomHighlightFacility::class;
    }
}
