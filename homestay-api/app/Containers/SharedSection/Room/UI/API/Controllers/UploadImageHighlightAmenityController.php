<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\SharedSection\Room\Actions\UploadImageHighlightAmenityAction;
use App\Containers\SharedSection\Room\UI\API\Requests\UploadImageHighlightAmenityRequest;
use App\Ship\Parents\Controllers\ApiController;

final class UploadImageHighlightAmenityController extends ApiController
{

    public function __invoke(UploadImageHighlightAmenityRequest $request, UploadImageHighlightAmenityAction $action)
    {
        try {
            $result = $action->run($request->validated());
            return Response::create($result)->created();
        } catch (\Exception $e) {
            return Response::create([
                'message' => 'Failed to upload images',
                'error' => $e->getMessage()
            ])->badRequest();
        }
    }
}
