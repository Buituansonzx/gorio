<?php

namespace App\Containers\SharedSection\Order\Tasks;

use App\Containers\SharedSection\Order\Models\Sms;
use App\Jobs\HandleBankSms;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ConfirmPaymentTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run(array $data)
    {
        $language = request()->header('Accept-Language', 'en');
        $sms = Sms::create([
            'content' => $data['message'],
            'sender' => $data['sender'],
        ]);
        dispatch(new HandleBankSms($sms, $language));
    }
}
