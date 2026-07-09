<?php

namespace App\Containers\AppSection\User\Actions;

use App\Containers\AppSection\User\Exceptions\FailedToDeleteUser;
use App\Containers\AppSection\User\UI\API\Requests\DeleteUserRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;

final class DeleteUserAction extends ParentAction
{
    public function __construct(
    ) {
    }

    public function run($request)
    {
        $user = Auth::user();
        $otp = $request['otp'] ?? null;
        if ($user->otp_code !== $otp) {
            throw new FailedToDeleteUser(404,'OTP code is invalid.');
        }
        $reason = $request['reason'] ?? null;

        $raw = $user->data;
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $data = [];
        }
        $data['deletion_reason'] = $reason;

        $user->data = $data;
        $user->save();
        $user->delete();

        $user->tokens()->update([
            'revoked' => true
        ]);
    }
}
