<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class AddMediaForRoomRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.area_group_id' => 'required|string|exists:room_image_area_group,id',
            'items.*.media' => 'required|file|mimes:jpg,jpeg,png|max:51200',
        ];
    }
}
