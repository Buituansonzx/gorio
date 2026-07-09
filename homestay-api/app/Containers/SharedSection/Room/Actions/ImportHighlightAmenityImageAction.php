<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportHighlightAmenityImageTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHighlightAmenityImageRequest;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHighlightAmenityRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportHighlightAmenityImageAction extends ParentAction
{
    public function run(ImportRoomHighlightAmenityImageRequest $request)
    {
        return app(ImportHighlightAmenityImageTask::class)->run(
            $request->file('files')
        );
    }
}
