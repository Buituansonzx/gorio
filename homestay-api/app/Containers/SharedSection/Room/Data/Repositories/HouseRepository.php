<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of House
 * @extends ParentRepository<TModel>
 */
class HouseRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
        'address' => 'like',
        'description' => 'like',
        'host_id' => '=',
    ];

    public function model(): string
    {
        return House::class;
    }

    /**
     * Get houses by host ID
     */
    public function getByHostId(int $hostId)
    {
        return $this->model->where('host_id', $hostId)->get();
    }

    /**
     * Get houses with active rooms
     */
    public function getHousesWithActiveRooms()
    {
        return $this->model->withActiveRooms()->get();
    }

    /**
     * Find house with rooms
     */
    public function findWithRooms(int $houseId): ?House
    {
        return $this->model->with(['rooms', 'host'])->find($houseId);
    }

    /**
     * Search houses by location
     */
    public function searchByLocation(string $address)
    {
        return $this->model->where('address', 'like', "%{$address}%")->get();
    }
}
