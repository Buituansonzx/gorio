<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\AttributeRepository;
use App\Containers\SharedSection\Room\Models\Attribute;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateAttributeTask extends ParentTask
{
    public function __construct(
        private readonly AttributeRepository $repository,
    ) {
    }

    public function run(array $data): Attribute
    {
        return $this->repository->create($data);
    }
}
