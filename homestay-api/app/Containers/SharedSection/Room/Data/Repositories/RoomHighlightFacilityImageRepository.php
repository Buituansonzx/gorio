<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomHighlightFacilityImage;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomHighlightFacilityImage
 *
 * @extends ParentRepository<TModel>
 */
final class RoomHighlightFacilityImageRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'facility_id' => '=',
        'image_url' => 'like',
        's3_key' => 'like',
        'is_cover' => '=',
        'order_index' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomHighlightFacilityImage::class;
    }
}
