<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Notification\Actions\FindNotificationByIdAction;
use App\Containers\AppSection\Notification\UI\API\Requests\FindNotificationByIdRequest;
use App\Containers\AppSection\Notification\UI\API\Transformers\NotificationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindNotificationByIdController extends ApiController
{
    public function __invoke(FindNotificationByIdRequest $request, FindNotificationByIdAction $action): JsonResponse
    {
        $notification = $action->run($request);

        return Response::create($notification, NotificationTransformer::class)->ok();
    }
}
