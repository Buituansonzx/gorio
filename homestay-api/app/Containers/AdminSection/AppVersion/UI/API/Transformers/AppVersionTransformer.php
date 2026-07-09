<?php

namespace App\Containers\AdminSection\AppVersion\UI\API\Transformers;

use App\Containers\SharedSection\AppVersion\Models\AppVersion;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class AppVersionTransformer extends ParentTransformer
{
    public function transform(AppVersion $appVersion): array
    {
        return [
            'object' => 'AppVersion',
            'id' => $appVersion->id,
            'platform' => $appVersion->platform,
            'version_name' => $appVersion->version_name,
            'version_code' => $appVersion->version_code,
            'is_force_update' => $appVersion->is_force_update,
            'update_url' => $appVersion->update_url,
            'title' => $appVersion->title,
            'content' => $appVersion->content,
            'status' => $appVersion->status,
            'created_at' => $appVersion->created_at->format('H:i:s d-m-Y'),
            'updated_at' => $appVersion->updated_at->format('H:i:s d-m-Y'),
        ];
    }
}
