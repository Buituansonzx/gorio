<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Attribute;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class AttributeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Attribute $attribute): array
    {
        return [
            'object' => $attribute->getResourceKey(),
            'id' => $attribute->id,
            'name' => $attribute->getTranslation('name', request()->header('Accept-Language')),
            'quantity' => $attribute->pivot->quantity,
        ];
    }
}
