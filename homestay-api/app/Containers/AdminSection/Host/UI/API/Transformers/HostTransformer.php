<?php

namespace App\Containers\AdminSection\Host\UI\API\Transformers;


use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class HostTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Host $host): array
    {
        return [
            'type' => $host->getResourceKey(),
            'id' => $host->id,
            'name' => $host->user->name,
            'email' => $host->user->email,
            'brand_name' => $host->business_name,
            'avatar' => S3Helper::getS3ImageUrl($host->avatar),
            'description' => $host->description,
            'address' => $host->address,
            'data' => $host->data,
            'hotline' => $host->user->phone_number,
            'is_active' => $host->is_active,
            'is_blocked' => $host->user->is_blocked,
            'status' => $host->user->status,
            'created_at' => $host->created_at,
        ];
    }
}
