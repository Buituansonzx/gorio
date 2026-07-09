<?php

namespace App\Containers\AdminSection\Voucher\Tasks;

use App\Containers\SharedSection\Order\Data\Repositories\VoucherRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListVoucherTask extends ParentTask
{
    public function __construct(private readonly VoucherRepository $voucherRepository)
    {
    }

    public function run($request)
    {
         return $this->voucherRepository->listVoucher($request);
    }
}
