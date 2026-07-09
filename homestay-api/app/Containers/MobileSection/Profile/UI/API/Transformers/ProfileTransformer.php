<?php

namespace App\Containers\MobileSection\Profile\UI\API\Transformers;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ProfileTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(User $user): array
    {
        return [
            'type' => $user->getResourceKey(),
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar' => S3Helper::getS3ImageUrl($user->avatar),
            'phone' => $user->phone,
            'gender' => $user->gender,
            'birth' => $user->birth,
        ];
    }
}
