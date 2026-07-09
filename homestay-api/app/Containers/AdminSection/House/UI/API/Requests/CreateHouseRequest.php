<?php

namespace App\Containers\AdminSection\House\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateHouseRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'host_id' => 'required|exists:hosts,id',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'guide_video' => 'nullable',
        ];
    }
}
