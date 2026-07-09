<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Host
 * @extends ParentRepository<TModel>
 */
class HostRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'business_name' => 'like',
        'is_active' => '=',
        'hotline' => 'like',
    ];

    public function model(): string
    {
        return Host::class;
    }

    /**
     * Get verified hosts
     */
    public function getVerifiedHosts()
    {
        return $this->model->verified()->get();
    }

    /**
     * Get hosts with active rooms
     */
    public function getHostsWithActiveRooms()
    {
        return $this->model->withActiveRooms()->get();
    }

    /**
     * Find host by user ID
     */
    public function findByUserId(int $userId): ?Host
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Get host with rooms count
     */
    public function getHostWithRoomsCount(int $hostId): ?Host
    {
        return $this->model->withCount(['rooms', 'houses'])
            ->find($hostId);
    }

    public function getAllHost($filters)
    {
        $query = $this->addRequestCriteria($filters)
            ->with('user');

        if (!empty($filters['keyword'])) {
            $query = $query->where(function ($q) use ($filters) {
                $q->whereHas('user', function ($q2) use ($filters) {
                    $q2->where('name', 'like', '%' . $filters['keyword'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['keyword'] . '%')
                        ->orWhere('phone_number', 'like', '%' . $filters['keyword'] . '%');
                });
            });
        }
        if (isset($filters['status'])) {
            $status = $filters['status'];
            if (in_array($status, [1, 0])) {
                $query = $query->where('is_active', $status);
            } elseif ($status == 3) {
                $query = $query->whereHas('user', function ($q) {
                    $q->where('is_blocked', 0);
                });
            } elseif ($status == 4) {
                $query = $query->whereHas('user', function ($q) {
                    $q->where('is_blocked', 1);
                });
            }
        }

        return $query->paginate($filters['page_size'] ?? 20);
    }
}
