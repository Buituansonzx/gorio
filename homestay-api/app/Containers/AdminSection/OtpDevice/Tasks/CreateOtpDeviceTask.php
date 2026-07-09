<?php

namespace App\Containers\AdminSection\OtpDevice\Tasks;

use App\Containers\SharedSection\OtpDevice\Data\Repositories\OtpDeviceRepository;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class CreateOtpDeviceTask extends ParentTask
{
    public function __construct(
        protected OtpDeviceRepository $repository
    ) {
    }

    public function run(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (Exception) {
            throw new CreateResourceFailedException();
        }
    }
}
