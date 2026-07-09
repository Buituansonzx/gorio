<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\AttributeRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListAttributesTask extends ParentTask
{
    public function __construct(
        private readonly AttributeRepository $repository,
    ) {
    }

    public function run(): Collection
    {
        return $this->repository->all();
    }
}
