<?php

namespace App\Containers\AdminSection\Host\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListingRoomByHostRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'int|nullable',
        ];
    }
}
