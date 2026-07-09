<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Room
 *
 * @extends ParentRepository<TModel>
 */
class RoomRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
        'description' => 'like',
        'house_id' => '=',
        'host_id' => '=',
        'room_type_id' => '=',
        'access_type_id' => '=',
        'status' => '=',
        'floor' => '=',
        'capacity' => '=',
        'area' => '=',
        'address' => 'like',
        'latitude' => '=',
        'longitude' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
        'code' => 'like',
        'district_id' => '=',
        'is_active' => '=',
    ];

    public function model(): string
    {
        return Room::class;
    }

    /**
     * Get rooms by host ID
     */
    public function getRoomsByHostId($hostId, $pageSize)
    {
        return $this->model->with('district','district.province','host','medias')->where('host_id', $hostId)->paginate($pageSize);
    }

    /**
     * Get rooms by house ID
     */
    public function getRoomsByHouseId(int $houseId)
    {
        return $this->model->where('house_id', $houseId)->get();
    }

    /**
     * Find room with host and house
     */
    public function findWithHostAndHouse(int $roomId): ?Room
    {
        return $this->model->with(['host', 'house'])->find($roomId);
    }

    /**
     * Get rooms by verified hosts only
     */
    public function getRoomsByVerifiedHosts()
    {
        return $this->model->whereHas('host', function ($query) {
            $query->where('verified_status', true);
        })->get();
    }
    public function listingRoomByHostId(string $hostId)
    {
        $pageSize = request()->page_size ?? 16;
        return $this->model->where('host_id', $hostId)->where('is_active', true)
            ->with(['host', 'comboPricing', 'roomType', 'accessType', 'district', 'fixedCheckTimeStd','images', 'orders.review','hourlyPricing','medias','specialOffers','fixedCheckTime','pricingPolicy'])
            ->paginate($pageSize);
    }

    public function listingRoomsAdmin(array $filters)
    {
        $query = $this->model;
        if(!empty($filters['search'])) {
            $query = $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
        if(!empty($filters['host_id'])) {
            $query = $query->where('host_id', $filters['host_id']);
        }
        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== '') {
            $query = $query->where('is_active', $filters['is_active']);
        }
        if (array_key_exists('house_id', $filters) && $filters['house_id'] !== '') {
            $query = $query->where('house_id', $filters['house_id']);
        }
        return $query->with(['host.user', 'district.province','medias','checkinMethods'])->paginate($filters['page_size'] ?? 20);
    }
}
