<?php

namespace App\Containers\ClientSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\RoomCheckinInstruction;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class CheckinInstructionTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomCheckinInstruction $instruction): array
    {
        return [
            'type' => $instruction->getResourceKey(),
            'id' => $instruction->id,
            'checkin_method_name' => $instruction->checkinMethod?->getTranslation('name', request()->header('Accept-Language')),
            'checkin_method_description' => $instruction->checkinMethod?->getTranslation('description', request()->header('Accept-Language')),
            'way_to_house_message' => $instruction->way_to_house_message,
            'direction_message' => $instruction->directions_message,
            'wifi_name' => $instruction->wifi_name,
            'wifi_password' => $instruction->wifi_password,
        ];
    }
}
