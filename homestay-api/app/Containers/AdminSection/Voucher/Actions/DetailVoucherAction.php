<?php

namespace App\Containers\AdminSection\Voucher\Actions;

use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DetailVoucherAction extends ParentAction
{
    public function run($voucherId)
    {
        $voucher = Voucher::find($voucherId);
        if(!$voucher){
            throw new \Exception('Voucher not found');
        }
        return $voucher;
    }
}
