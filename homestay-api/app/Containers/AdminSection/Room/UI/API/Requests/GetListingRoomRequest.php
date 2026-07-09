<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetListingRoomRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'sometimes|integer',
            'search' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'host_id' => 'sometimes|string',
            'house_id' => 'sometimes|string',
        ];
    }
}
