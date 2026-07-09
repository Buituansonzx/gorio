<?php

namespace App\Containers\SharedSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class MediaRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'room_id' => 'sometimes|string|exists:rooms,id',
            'images' => 'required|array',
            'images.*.file' => 'required|image|mimes:jpg,jpeg,png,webp',
            'images.*.index' => 'required|integer',
            'is_cover' => 'sometimes|boolean',
            'room_image_area_group_id' => 'sometimes|string|exists:room_image_area_group,id',
            'highlight_amenity_id' => 'sometimes|string|exists:room_highlight_amenities,id',
        ];

    }
}
