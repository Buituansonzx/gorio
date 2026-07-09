<?php

namespace App\Containers\SharedSection\Order\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\SharedSection\Order\Actions\CreateReviewAction;
use App\Containers\SharedSection\Order\UI\API\Requests\CreateReviewRequest;
use App\Containers\SharedSection\Order\UI\API\Transformers\ReviewTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class ReviewController extends ApiController
{
    public function create(CreateReviewRequest $request, CreateReviewAction $action)
    {
         $review  = $action->run($request);
         return Response::create($review, ReviewTransformer::class);
    }
}
