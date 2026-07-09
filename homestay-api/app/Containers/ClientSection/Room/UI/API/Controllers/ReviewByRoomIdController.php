<?php

namespace App\Containers\ClientSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Room\Actions\ReviewByRoomIdAction;
use App\Containers\ClientSection\Room\UI\API\Transformers\ReviewByRoomIdTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class ReviewByRoomIdController extends ApiController
{
    public function index(Request $request, ReviewByRoomIdAction $action)
    {
        $roomId = $request->id;

        $reviews = $action->run($roomId);

        return Response::create($reviews, ReviewByRoomIdTransformer::class)->ok();
    }
}
