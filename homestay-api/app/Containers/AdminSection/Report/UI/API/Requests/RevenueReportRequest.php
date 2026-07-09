<?php

namespace App\Containers\AdminSection\Report\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class RevenueReportRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ];
    }
}
