<?php

namespace App\Containers\AdminSection\Order\Actions;

use App\Containers\SharedSection\Order\Data\Repositories\OrderRepository;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListingOrderAction extends ParentAction
{
    public function __construct(private readonly OrderRepository $repository)
    {
    }

    public function run($request)
    {
        return $this->repository->listingOrders($request->validated());
    }

}
