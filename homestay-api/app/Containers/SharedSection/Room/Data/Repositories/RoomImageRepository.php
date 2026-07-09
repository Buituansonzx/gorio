<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomImage
 *
 * @extends ParentRepository<TModel>
 */
class RoomImageRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'image_url' => 'like',
        's3_key' => 'like',
        'is_cover' => '=',
        'order_index' => '=',
        'area_type' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomImage::class;
    }
}
