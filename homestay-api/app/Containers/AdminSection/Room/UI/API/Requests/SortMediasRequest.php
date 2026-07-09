<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class SortMediasRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'media' => 'required|array',
            'media.*.id' => 'required|uuid|exists:medias,id',
            'media.*.sort_index' => 'required|integer',
        ];
    }
}
