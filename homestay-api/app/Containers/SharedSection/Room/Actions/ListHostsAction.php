<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\ListHostsTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListHostsAction extends ParentAction
{
    public function __construct(
        private readonly ListHostsTask $listHostsTask,
    ) {
    }

    public function run(): Collection
    {
        return $this->listHostsTask->run();
    }

    public function getVerifiedHosts(): Collection
    {
        return $this->listHostsTask->getVerifiedHosts();
    }

    public function getHostsWithActiveRooms(): Collection
    {
        return $this->listHostsTask->getHostsWithActiveRooms();
    }
}
