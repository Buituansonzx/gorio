<?php

namespace App\Containers\SharedSection\Profile\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Host
 *
 * @extends ParentRepository<TModel>
 */
final class GetProfileHostRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return Host::class;
    }

    public function findProfile(string $hostId): ?Host
    {
        return $this->model()::query()
            ->with(['user', 'rooms','rooms.fixedCheckTimeStd', 'rooms.roomType', 'rooms.district', 'rooms.images','rooms.orders.review'])
            ->find($hostId);
    }
}
