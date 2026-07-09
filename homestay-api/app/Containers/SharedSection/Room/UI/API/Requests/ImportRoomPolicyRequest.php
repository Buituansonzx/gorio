<?php

namespace App\Containers\SharedSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ImportRoomPolicyRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'files'   => 'required|array',
            'files.*' => 'file|mimes:xlsx,csv',
        ];
    }
}
