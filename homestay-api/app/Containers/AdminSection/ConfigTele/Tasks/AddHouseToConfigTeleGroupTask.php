<?php

namespace App\Containers\AdminSection\ConfigTele\Tasks;

use App\Containers\AdminSection\ConfigTele\Data\Repositories\ConfigTeleGroupRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Exception;

class AddHouseToConfigTeleGroupTask extends ParentTask
{
    public function __construct(
        protected ConfigTeleGroupRepository $repository
    ) {
    }

    public function run($configTeleGroupId, array $houseIds)
    {
        try {
            $configTeleGroup = $this->repository->find($configTeleGroupId);
            $configTeleGroup->houses()->sync($houseIds);
            
            return $configTeleGroup;
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Add house to config failed: ' . $exception->getMessage());
        }
    }
}
