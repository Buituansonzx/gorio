<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomAttribute;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomAttributeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'attribute',
    ];

    public function transform(RoomAttribute $roomAttribute): array
    {
        return [
            'object' => $roomAttribute->getResourceKey(),
            'attribute_id' => $roomAttribute->attribute_id,
            'attribute_name' => $roomAttribute->getTranslation('name', request()->header('Accept-Language')),
            'quantity' => $roomAttribute->quantity,
        ];
    }

    public function includeAttribute(RoomAttribute $roomAttribute): \League\Fractal\Resource\Item
    {
        return $this->item($roomAttribute->attribute, new AttributeTransformer());
    }
}
