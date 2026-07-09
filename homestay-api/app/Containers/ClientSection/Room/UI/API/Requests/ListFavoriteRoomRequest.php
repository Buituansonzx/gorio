<?php

namespace App\Containers\ClientSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListFavoriteRoomRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
