<?php

namespace App\Containers\SharedSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\SharedSection\Profile\Actions\GetReviewAction;
use App\Containers\SharedSection\Profile\UI\API\Requests\GetReviewRequest;
use App\Containers\SharedSection\Profile\UI\API\Transformers\ReviewTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetReviewController extends ApiController
{

    public function __invoke(GetReviewRequest $request, GetReviewAction $action)
    {
        $hostId = $request->id;
        $reviews = $action->run($hostId);

        return Response::create($reviews, ReviewTransformer::class);
    }

}
