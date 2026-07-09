<?php

namespace App\Containers\AdminSection\OtpDevice\Tasks;

use App\Containers\SharedSection\OtpDevice\Data\Repositories\OtpDeviceRepository;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class UpdateOtpDeviceTask extends ParentTask
{
    public function __construct(
        protected OtpDeviceRepository $repository
    ) {
    }

    public function run($id, array $data)
    {
        try {
            return $this->repository->update($data, $id);
        } catch (Exception) {
            throw new UpdateResourceFailedException();
        }
    }
}
