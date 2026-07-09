<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Data\Repositories\RoomImageRepository as SharedRoomImageRepository;
use App\Containers\ClientSection\Room\Models\RoomImage;

/**
 * Class RoomImageRepository
 * 
 * Client-specific RoomImage repository that extends the shared RoomImage repository.
 * 
 * @template TModel of RoomImage
 * @extends SharedRoomImageRepository<TModel>
 * @package App\Containers\ClientSection\Room\Data\Repositories
 */
class RoomImageRepository extends SharedRoomImageRepository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return RoomImage::class;
    }

    /**
     * Get optimized images for client display
     */
    public function getClientImages(int $roomId)
    {
        return $this->model->where('room_id', $roomId)
            ->ordered()
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->optimized_url,
                    'alt_text' => $image->alt_text,
                    'is_primary' => $image->is_primary,
                ];
            });
    }

    /**
     * Get primary image for room preview
     */
    public function getPrimaryImage(int $roomId)
    {
        return $this->model->where('room_id', $roomId)
            ->primary()
            ->first();
    }
}
