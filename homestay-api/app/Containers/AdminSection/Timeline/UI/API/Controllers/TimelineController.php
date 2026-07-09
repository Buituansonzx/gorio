<?php

namespace App\Containers\AdminSection\Timeline\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Timeline\Actions\GetEventsAction;
use App\Containers\AdminSection\Timeline\Actions\RoomsForTimelineAction;
use App\Containers\AdminSection\Timeline\UI\API\Requests\GetEventsRequest;
use App\Containers\AdminSection\Timeline\UI\API\Transformers\EventTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class TimelineController extends ApiController
{
    public function index(GetEventsRequest $request, GetEventsAction $action)
    {
        $data = $action->run($request->validated());
        return response()->json([
            'message' => 'Events retrieved successfully.',
            'data' => $data,
        ]);
    }

    public function getRoomsForTimeline(Request $request, RoomsForTimelineAction $action)
    {
        $hostId = $request->id;
        $rooms = $action->run($hostId);
        return response()->json([
            'message' => 'Rooms retrieved successfully.',
            'data' => $rooms,
        ]);
    }
}
