<?php

namespace App\Containers\AppSection\Auth\UI\API\Controllers;

use App\Containers\AppSection\Auth\Actions\CheckPhoneAction;
use App\Containers\AppSection\Auth\UI\API\Requests\CheckPhoneRequest;
use App\Containers\SharedSection\Room\Models\SupportContact;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CheckPhoneController extends ApiController
{
    public function checkPhone(CheckPhoneRequest $request)
    {
        $phone = $request->input('phone');
        
        $data = $request->validated();

        // Lấy thông tin phân tích số điện thoại
        $phoneAnalysis = $request->getPhoneAnalysis();



        if ($phoneAnalysis['success']) {
            $data['phone_analysis'] = $phoneAnalysis;
        }


        $result = app(CheckPhoneAction::class)->run($data);

        $supportResponse = null;
        $supportSetting = DB::table('setting')->where('key', 'check_phone_support_status')->first();

        // Kiểm tra xem đã bật chưa (ghi vào DB value bằng 1, true, hoặc on)
        $isEnabled = $supportSetting && in_array(strtolower(trim($supportSetting->value)), ['1', 'true', 'on', 'yes']);

        if ($isEnabled) {
            $contacts = SupportContact::where('label', SupportContact::LABEL_ZALO)->orWhere('label', SupportContact::LABEL_FACEBOOK)->get();
            $channels = [];
            
            foreach ($contacts as $contact) {
                $channels[] = [
                    'label' => $contact->label,
                    'value' => $contact->value
                ];
            }

            $supportResponse = [
                'enabled' => true,
                'message' => 'Hệ thống xác minh số điện thoại hiện đang bị gián đoạn. Vui lòng chọn một kênh bên dưới để được hỗ trợ.',
                'channels' => $channels
            ];
        }

        $response = [
            'success' => true,
            'message' => $result ? "Người dùng đã tồn tại trên hệ thống" : "Người dùng chưa tồn tại trên hệ thống",
            'data' => (bool)$result
        ];

        if ($supportResponse !== null) {
            $response['support'] = $supportResponse;
        }

        return response()->json($response);
    }
}
