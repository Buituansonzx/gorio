<?php

namespace App\Containers\AdminSection\ConfigTele\Tasks;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use App\Containers\AdminSection\ConfigTele\Data\Repositories\ConfigTeleGroupRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Prettus\Repository\Exceptions\RepositoryException;

class GetAllConfigTeleGroupsTask extends ParentTask
{
    public function __construct(
        protected ConfigTeleGroupRepository $repository
    ) {
    }

    public function run()
    {
        return $this->repository->with('houses')->paginate();
    }
}
