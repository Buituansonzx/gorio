<?php

namespace App\Containers\AdminSection\House\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class HouseTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(House $house): array
    {
        $room = $house->rooms->first();

        $checkinInstruction = $room
            ?->checkinMethods
            ?->first()
            ?->pivot
            ?->way_to_house_message;
        return [
            'type' => $house->getResourceKey(),
            'id' => $house->id,
            'name' => $house->name,
            'address' => $house->address,
            'host_id' => $house->host_id,
            'latitude' => $house->latitude,
            'longitude' => $house->longitude,
            'district_id' => $house->district_id,
            'is_active' => (bool) $house->is_active,
            'checkin_instruction' => $checkinInstruction,
            'guide_video' => $house->guide_video
                ? app(S3Helper::class)->getFileUrl($house->guide_video)
                : null,
            'created_at' => $house->created_at->toDateTimeString(),
            'updated_at' => $house->updated_at->toDateTimeString(),
        ];
    }
}
