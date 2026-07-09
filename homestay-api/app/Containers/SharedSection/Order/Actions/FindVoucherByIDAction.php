<?php

namespace App\Containers\SharedSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindVoucherByIDAction extends ParentAction
{
    public function run($voucherId)
    {
        return Voucher::findOrFail($voucherId);
    }
}
