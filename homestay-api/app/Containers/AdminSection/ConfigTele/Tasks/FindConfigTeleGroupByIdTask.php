<?php

namespace App\Containers\AdminSection\ConfigTele\Tasks;

use App\Containers\AdminSection\ConfigTele\Data\Repositories\ConfigTeleGroupRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class FindConfigTeleGroupByIdTask extends ParentTask
{
    public function __construct(
        protected ConfigTeleGroupRepository $repository
    ) {
    }

    public function run($id)
    {
        try {
            return $this->repository->find($id);
        } catch (Exception $exception) {
            throw new NotFoundHttpException('ConfigTeleGroup not found.' . $exception->getMessage());
        }
    }
}
