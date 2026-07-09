<?php

namespace App\Containers\AdminSection\Timeline\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetEventsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'host_id' => 'nullable|string|exists:hosts,id',
        ];
    }
}
