<?php

namespace App\Containers\SharedSection\Profile\Actions;

use App\Containers\SharedSection\Profile\UI\API\Requests\UploadAvatarHostRequest;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Storage;

final class UploadAvatarHostAction extends ParentAction
{
    public function run(UploadAvatarHostRequest $request, $hostId)
    {
        $host = Host::find($hostId);
        $file = $request->file('file');
        $dir = "host/avatar/{$host->id}";
        $path = $file->store($dir, 's3');
        if ($host) {
            $host->avatar = $path;
            $host->save();
        }
        return $path;
    }
}
