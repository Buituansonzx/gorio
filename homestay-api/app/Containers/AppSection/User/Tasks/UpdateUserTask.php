<?php

namespace App\Containers\AppSection\User\Tasks;

use App\Containers\AppSection\User\Data\Repositories\UserRepository;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

final class UpdateUserTask extends ParentTask
{


    public function run($data)
    {
        $userId = Auth::id();

        $user = User::findOrFail($userId);
        $updates = [];
        if (isset($data['first_name'])) {
            $updates['first_name'] = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $updates['last_name'] = $data['last_name'];
        }
        if (isset($data['first_name']) || isset($data['last_name'])) {
            $updates['name'] = ($data['first_name'] ?? $user->first_name) . ' ' . ($data['last_name'] ?? $user->last_name);
        }

        if (isset($data['email'])) {
            $updates['email'] = $data['email'];
        }

        if (isset($data['gender'])) {
            $updates['gender'] = $data['gender'];
        }

        if (isset($data['birth'])) {
            $updates['birth'] = $data['birth'];
        }
        if (!empty($data['avatar'])) {
            $dir = "users/avatar/{$user->id}";
            $updates['avatar'] = $data['avatar']->store($dir, 's3');
        }

        $user->update($updates);
        return $user->fresh();
    }
}
