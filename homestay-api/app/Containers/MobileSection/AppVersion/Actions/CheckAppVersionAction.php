<?php

namespace App\Containers\MobileSection\AppVersion\Actions;

use App\Containers\MobileSection\AppVersion\UI\API\Requests\CheckAppVersionRequest;
use App\Containers\SharedSection\AppVersion\Models\AppVersion;
use App\Ship\Parents\Actions\Action as ParentAction;

class CheckAppVersionAction extends ParentAction
{
    public function run(CheckAppVersionRequest $request)
    {
        $platform = $request->platform;
        $currentCode = $request->version_code;

        // Tìm version mới nhất đang active của platform này
        $latestVersion = AppVersion::where('platform', $platform)
            ->where('status', 1)
            ->orderBy('version_code', 'desc')
            ->first();

        // Nếu version code mới nhất lớn hơn version code truyền lên thì cần update
        if ($latestVersion && $latestVersion->version_code > $currentCode) {
            return $latestVersion;
        }

        return null;
    }
}
