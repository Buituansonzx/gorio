<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\SharedSection\Room\Actions\GetBusyTimeAction;
use App\Containers\SharedSection\Room\UI\API\Requests\GetBusyTimeRequest;
use App\Containers\SharedSection\Room\UI\API\Transformers\BusyTimeTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetBusyTimeController extends ApiController
{

    public function __invoke(GetBusyTimeRequest $request, GetBusyTimeAction $action)
    {
        $roomId = $request->id;
        $busyTime = $action->run($roomId);
        return Response::create($busyTime, BusyTimeTransformer::class);
    }
}
