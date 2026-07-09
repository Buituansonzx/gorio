<?php

namespace App\Containers\AdminSection\ConfigTele\Tasks;

use App\Containers\AdminSection\ConfigTele\Data\Repositories\ConfigTeleGroupRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class DeleteConfigTeleGroupTask extends ParentTask
{
    public function __construct(
        protected ConfigTeleGroupRepository $repository
    ) {
    }

    public function run($id): int
    {
        try {
            return $this->repository->delete($id);
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Delete resource failed.' . $exception->getMessage());
        }
    }
}
