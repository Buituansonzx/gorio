<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomType;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomTypeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomType $roomType): array
    {
        $locale = request()->header('Accept-Language') ?? app()->getLocale() ?? 'en';
        
        return [
            'object' => $roomType->getResourceKey(),
            'id' => $roomType->id,
            'name' => $roomType->getTranslation('name', $locale),
            'description' => $roomType->getTranslation('description', $locale),
        ];
    }
}
