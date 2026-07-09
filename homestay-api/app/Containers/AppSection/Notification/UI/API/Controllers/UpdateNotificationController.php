<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Notification\Actions\UpdateNotificationAction;
use App\Containers\AppSection\Notification\UI\API\Requests\UpdateNotificationRequest;
use App\Containers\AppSection\Notification\UI\API\Transformers\NotificationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateNotificationController extends ApiController
{
    public function __invoke(UpdateNotificationRequest $request, UpdateNotificationAction $action): JsonResponse
    {
        $notification = $action->run($request);

        return Response::create($notification, NotificationTransformer::class)->ok();
    }
}
