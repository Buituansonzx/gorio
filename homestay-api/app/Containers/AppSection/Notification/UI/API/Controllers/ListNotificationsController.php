<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Notification\Actions\ListNotificationsAction;
use App\Containers\AppSection\Notification\UI\API\Requests\ListNotificationsRequest;
use App\Containers\AppSection\Notification\UI\API\Transformers\NotificationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListNotificationsController extends ApiController
{
    public function __invoke(ListNotificationsRequest $request, ListNotificationsAction $action): JsonResponse
    {
        $notifications = $action->run($request);

        return Response::create($notifications, NotificationTransformer::class)->ok();
    }
}
