<?php

namespace App\Containers\AppSection\Support\UI\API\Controllers;

use App\Ship\Parents\Controllers\ApiController;

final class SupportController extends ApiController
{
    public function __invoke()
    {
        return response()->json([
            'phone' => config('support.phone'),
            'email' => config('support.email'),
            'fanpage' => config('support.fanpage'),
        ]);
    }
}
