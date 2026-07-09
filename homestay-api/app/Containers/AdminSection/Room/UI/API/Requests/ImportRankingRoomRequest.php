<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ImportRankingRoomRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ];
    }
}
