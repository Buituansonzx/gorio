<?php

namespace App\Containers\AdminSection\ConfigTele\Tasks;

use App\Containers\AdminSection\ConfigTele\Data\Repositories\ConfigTeleGroupRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class CreateConfigTeleGroupTask extends ParentTask
{
    public function __construct(
        protected ConfigTeleGroupRepository $repository
    ) {
    }

    public function run(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Create resource failed.' . $exception->getMessage());
        }
    }
}
