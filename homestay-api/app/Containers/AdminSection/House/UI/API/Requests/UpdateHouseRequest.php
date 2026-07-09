<?php

namespace App\Containers\AdminSection\House\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateHouseRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:500',
            'district_id' => 'sometimes|string|exists:districts,id',
            'is_active' => 'sometimes|boolean',
            'checkin_instruction' => 'sometimes|nullable|string',
            'guide_video' => 'sometimes|file|mimes:mp4,mov,avi,mkv',
        ];
    }
}
