<?php

namespace App\Containers\SharedSection\Profile\UI\API\Controllers;

use App\Containers\SharedSection\Profile\Actions\UploadAvatarHostAction;
use App\Containers\SharedSection\Profile\UI\API\Requests\UploadAvatarHostRequest;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Controllers\ApiController;

final class UploadAvatarHostController extends ApiController
{
    public function upload(UploadAvatarHostRequest $request, UploadAvatarHostAction $action)
    {
        $hostId = $request->id;
        $avatarUrl = $action->run($request,$hostId);
        return response()->json( ['data' => $avatarUrl] );
    }
}
