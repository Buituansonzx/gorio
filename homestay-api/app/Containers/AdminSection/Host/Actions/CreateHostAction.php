<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Helpers\PhoneHelper;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Validation\ValidationException;

final class CreateHostAction extends ParentAction
{
    public function run($data)
    {
        $phone = PhoneHelper::analyzePhone($data['phone'], 'VN');
        $user = User::where('phone', $phone['formatted']['e164'])->first();
        if ($user) {
            throw ValidationException::withMessages([
                'phone' => ['Phone number already exists'],
            ]);
        }
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $phone['formatted']['e164'],
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'phone_number' => $data['phone'],
            'phone_code' => '84',
            'country_code' => 'VN',
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        $host = Host::create([
            'user_id' => $user->id,
            'business_name' => $user->name,
            'description' => $data['description'] ?? '',
            'address' => $data['address'],
            'hotline' => $data['phone'],
            'is_active' => true,
            'verified_status' => true,
        ]);
        return $host;
    }
}
