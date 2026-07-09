<?php

namespace App\Containers\AppSection\User\Data\Repositories;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of User
 *
 * @extends ParentRepository<TModel>
 */
final class UserRepository extends ParentRepository
{
    protected $fieldSearchable = [

    ];

    public function model(): string
    {
        return config('auth.providers.users.model');
    }

    public function getAllUser(array $filters = [])
    {
        $query = $this->model;
        if (!empty($filters['keyword'])) {
            $query = $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('phone_number', 'like', '%' . $filters['keyword'] . '%');
            });
        }
        if (!empty($filters['status'])) {
            $status = $filters['status'];

            if (in_array($status, [1, -1])) {
                $query = $query->where('status', $status);
            } elseif ($status == 3) {
                $query = $query->where('is_blocked', 0);
            } elseif ($status == 4) {
                $query = $query->where('is_blocked', 1);
            }
        }
        return $query->orderBy('created_at', 'desc')->paginate($filters['page_size'] ?? 20);
    }

}
