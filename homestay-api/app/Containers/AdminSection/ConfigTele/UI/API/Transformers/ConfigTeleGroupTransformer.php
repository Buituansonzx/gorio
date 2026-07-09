<?php

namespace App\Containers\AdminSection\ConfigTele\UI\API\Transformers;

use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class ConfigTeleGroupTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'houses',
    ];

    protected array $availableIncludes = [
    ];

    public function transform(ConfigTeleGroup $configTeleGroup): array
    {
        return [
            'id' => $configTeleGroup->id,
            'name' => $configTeleGroup->name,
            'chat_id' => $configTeleGroup->chat_id,
            'bot_token' => $configTeleGroup->bot_token,
            'is_active' => $configTeleGroup->is_active,
            'created_at' => $configTeleGroup->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $configTeleGroup->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function includeHouses(ConfigTeleGroup $configTeleGroup)
    {
        return $this->collection($configTeleGroup->houses, function ($house) {
            return [
                'id' => $house->id,
                'name' => $house->name,
            ];
        });
    }
}
