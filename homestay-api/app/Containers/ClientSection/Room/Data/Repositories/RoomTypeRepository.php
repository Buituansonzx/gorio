<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Data\Repositories\RoomTypeRepository as SharedRoomTypeRepository;
use App\Containers\ClientSection\Room\Models\RoomType;

/**
 * Class RoomTypeRepository
 * 
 * Client-specific RoomType repository that extends the shared RoomType repository.
 * 
 * @template TModel of RoomType
 * @extends SharedRoomTypeRepository<TModel>
 * @package App\Containers\ClientSection\Room\Data\Repositories
 */
class RoomTypeRepository extends SharedRoomTypeRepository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return RoomType::class;
    }

    /**
     * Get active room types for client selection
     */
    public function getActiveForClient()
    {
        return $this->model->activeForClient()
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get room types with room count for client browsing
     */
    public function getWithRoomCount()
    {
        return $this->model->activeForClient()
            ->withCount(['rooms' => function ($query) {
                $query->where('status', 'active');
            }])
            ->having('rooms_count', '>', 0)
            ->orderBy('name', 'asc')
            ->get();
    }
}
