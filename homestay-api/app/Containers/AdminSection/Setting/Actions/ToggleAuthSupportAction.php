<?php

namespace App\Containers\AdminSection\Setting\Actions;

use App\Containers\AdminSection\Setting\UI\API\Requests\ToggleAuthSupportRequest;
use Illuminate\Support\Facades\DB;
use App\Ship\Parents\Actions\Action as ParentAction;

class ToggleAuthSupportAction extends ParentAction
{
    public function run(ToggleAuthSupportRequest $request)
    {
        $isEnabled = $request->boolean('is_enabled');
        $valueStr = $isEnabled ? '1' : '0';

        $exists = DB::table('setting')->where('key', 'check_phone_support_status')->exists();
        if ($exists) {
            DB::table('setting')
                ->where('key', 'check_phone_support_status')
                ->update(['value' => $valueStr, 'updated_at' => now()]);
        } else {
            DB::table('setting')->insert([
                'key' => 'check_phone_support_status',
                'value' => $valueStr,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return (object)[
            'key' => 'check_phone_support_status',
            'value' => $valueStr
        ];
    }
}
