<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Room
 *
 * @extends ParentRepository<TModel>
 */
final class GetRoomImageAreaGroupRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return RoomImageGroup::class;
    }


    public function getRoomImageAreaGroups()
    {
       return $this->model->get();
    }
}
