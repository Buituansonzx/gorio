<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\SharedSection\Room\Data\Repositories\HostRepository;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetHostAction extends ParentAction
{
    public function __construct(private readonly HostRepository $hostRepository)
    {
    }

    public function run($request)
    {
        return $this->hostRepository->getAllHost($request->validated());
    }
}
