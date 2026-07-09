<?php

namespace App\Containers\SharedSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class BlockRoomRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_date',
        ];
    }
}
