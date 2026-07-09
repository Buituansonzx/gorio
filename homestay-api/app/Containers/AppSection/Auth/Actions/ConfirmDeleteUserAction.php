<?php

namespace App\Containers\AppSection\Auth\Actions;

use App\Jobs\SendOtpSmsJob;
use App\Ship\Helpers\PhoneHelper;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ConfirmDeleteUserAction extends ParentAction
{
    public function run(Request $request)
    {
        $otpCode = $this->generateOTP();
        $user = Auth::user();
        $user->update(['otp_code' => $otpCode]);
        $phoneNumber = $user->phone;
        SendOtpSmsJob::dispatch($phoneNumber, $otpCode);
    }

    private function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
