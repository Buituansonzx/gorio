<?php

namespace App\Containers\AdminSection\ConfigTele\Tasks;

use App\Containers\AdminSection\ConfigTele\Data\Repositories\ConfigTeleGroupRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class UpdateConfigTeleGroupTask extends ParentTask
{
    public function __construct(
        protected ConfigTeleGroupRepository $repository
    ) {
    }

    public function run($id, array $data)
    {
        try {
            return $this->repository->update($data, $id);
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Update resource failed.' . $exception->getMessage());
        }
    }
}
