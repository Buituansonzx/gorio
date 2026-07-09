<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Notification\Actions\CreateNotificationAction;
use App\Containers\AppSection\Notification\UI\API\Requests\CreateNotificationRequest;
use App\Containers\AppSection\Notification\UI\API\Transformers\NotificationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateNotificationController extends ApiController
{
    public function __invoke(CreateNotificationRequest $request, CreateNotificationAction $action): JsonResponse
    {
        $notification = $action->run($request);

        return Response::create($notification, NotificationTransformer::class)->created();
    }
}
