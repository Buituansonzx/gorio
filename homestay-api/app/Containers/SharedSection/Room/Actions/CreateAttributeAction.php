<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Attribute;
use App\Containers\SharedSection\Room\Tasks\CreateAttributeTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateAttributeAction extends ParentAction
{
    public function __construct(
        private readonly CreateAttributeTask $createAttributeTask,
    ) {
    }

    public function run(array $data): Attribute
    {
        return $this->createAttributeTask->run($data);
    }
}
