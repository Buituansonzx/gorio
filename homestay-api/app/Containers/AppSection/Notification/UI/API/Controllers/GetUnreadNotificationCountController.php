<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use App\Containers\AppSection\Notification\Actions\GetUnreadNotificationCountAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class GetUnreadNotificationCountController extends ApiController
{
    public function __invoke(): JsonResponse
    {
        $count = app(GetUnreadNotificationCountAction::class)->run();

        return response()->json([
            'unread_count' => $count,
        ]);
    }
}
