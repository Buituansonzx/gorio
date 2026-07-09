<?php

namespace App\Containers\SharedSection\Profile\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetListingRoomByHostIdRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'sometimes|integer',
        ];
    }
}
