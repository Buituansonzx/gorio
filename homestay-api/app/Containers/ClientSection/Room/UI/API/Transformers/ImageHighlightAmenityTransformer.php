<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenityImage;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ImageHighlightAmenityTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomHighlightAmenityImage $highlightAmenityImage): array
    {
        return [
            'type' => $highlightAmenityImage->getResourceKey(),
            'image_url' => $highlightAmenityImage->image_url,
            'file_name' => $highlightAmenityImage->file_name,
            'file_size' => $highlightAmenityImage->file_size,
            'content_type' => $highlightAmenityImage->content_type,
        ];
    }
}
