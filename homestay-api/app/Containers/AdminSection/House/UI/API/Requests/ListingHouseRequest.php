<?php

namespace App\Containers\AdminSection\House\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListingHouseRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'search' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
            'district_id' => 'sometimes|string|exists:districts,id',
            'page_size' => 'sometimes|integer|min:1|max:9999999999',
        ];
    }
}
