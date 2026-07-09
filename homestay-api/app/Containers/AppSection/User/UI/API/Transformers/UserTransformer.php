<?php

namespace App\Containers\AppSection\User\UI\API\Transformers;

use App\Containers\AppSection\Authorization\UI\API\Transformers\PermissionTransformer;
use App\Containers\AppSection\Authorization\UI\API\Transformers\RoleTransformer;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use League\Fractal\Resource\Collection;

class UserTransformer extends ParentTransformer
{
    protected array $availableIncludes = [
        'roles',
        'permissions',
    ];

    protected array $defaultIncludes = [];

    public function transform(User $user): array
    {
        return [
            'type' => $user->getResourceKey(),
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'birth' => $user->birth?->format('Y-m-d'),
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            'status' => $user->status,
            'is_blocked' => $user->is_blocked,
        ];
    }

    public function includeRoles(User $user): Collection
    {
        return $this->collection($user->roles, new RoleTransformer());
    }

    public function includePermissions(User $user): Collection
    {
        return $this->collection($user->permissions, new PermissionTransformer());
    }
}
