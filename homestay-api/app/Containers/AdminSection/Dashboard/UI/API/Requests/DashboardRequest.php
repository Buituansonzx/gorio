<?php

namespace App\Containers\AdminSection\Dashboard\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DashboardRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:day,month,year',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'display' => 'sometimes|string',
        ];
    }
}
