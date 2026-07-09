<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Notification\Actions\DeleteNotificationAction;
use App\Containers\AppSection\Notification\UI\API\Requests\DeleteNotificationRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteNotificationController extends ApiController
{
    public function __invoke(DeleteNotificationRequest $request, DeleteNotificationAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
