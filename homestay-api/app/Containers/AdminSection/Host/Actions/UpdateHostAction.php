<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateHostAction extends ParentAction
{
    public function run($hostId, $data)
    {
        $host = Host::findOrFail($hostId);
        $user = $host->user;

        // Update user fields
        if (!empty($data['first_name'])) {
            $user->first_name = $data['first_name'];
        }

        if(!empty($data['description'])){
            $host->description = $data['description'];
        }

        if(!empty($data['brand_name'])){
            $host->business_name = $data['brand_name'];
        }
        if(!empty($data['avatar']) ?? null){
            $file = $data['avatar'];
            $dir = "host/avatar/{$host->id}";
            $path = $file->store($dir, 's3');
            $host->avatar = $path;
        }

        if (!empty($data['last_name'])) {
            $user->last_name = $data['last_name'];
        }

        if (!empty($data['first_name']) || !empty($data['last_name'])) {
            $user->name = trim(
                ($data['first_name'] ?? $user->first_name) . ' ' .
                ($data['last_name'] ?? $user->last_name)
            );
        }

        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }

        if (!empty($data['phone_number'])) {
            $user->phone_number = $data['phone_number'];
            $user->phone = $data['phone_number'];
            $host->hotline = $data['phone_number'];
        }

        if (!empty($data['is_active'])) {
            $host->is_active = $data['is_active'];
        }

        $user->save();
        $host->save();

        return $host->load('user');
    }
}
