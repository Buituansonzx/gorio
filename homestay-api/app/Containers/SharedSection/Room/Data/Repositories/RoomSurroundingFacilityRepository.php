<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomSurroundingFacility;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomSurroundingFacility
 *
 * @extends ParentRepository<TModel>
 */
final class RoomSurroundingFacilityRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'facility_id' => '=',
        'distance' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomSurroundingFacility::class;
    }
}
