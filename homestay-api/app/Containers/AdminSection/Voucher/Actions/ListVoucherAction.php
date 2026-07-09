<?php

namespace App\Containers\AdminSection\Voucher\Actions;

use App\Containers\AdminSection\Voucher\Tasks\ListVoucherTask;
use App\Containers\AdminSection\Voucher\UI\API\Requests\ListVoucherRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListVoucherAction extends ParentAction
{
    public function __construct(private readonly ListVoucherTask $listVoucherTask)
    {
    }

    public function run(ListVoucherRequest $request)
    {
        $data = $this->listVoucherTask->run($request->validated());
        return $data;
    }

}
