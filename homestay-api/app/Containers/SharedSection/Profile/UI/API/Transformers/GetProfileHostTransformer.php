<?php

namespace App\Containers\SharedSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class GetProfileHostTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'review'
    ];

    protected array $availableIncludes = [];

    public function transform(Host $host)
    {
        return [
            'type' => $host->getResourceKey(),
            'id' => $host->id,
            'business_name' => $host->business_name,
            'description' => $host->description,
            'avatar' => S3Helper::getS3ImageUrl($host->avatar),
            'start_day' => $host->created_at->format('Y-m-d'),
        ];
    }

    public function includeReview(Host $host)
    {
        return $this->primitive($host->data ?? []);
    }
}
