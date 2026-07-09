<?php

namespace App\Ship\Services;

use App\Containers\SharedSection\OtpDevice\Models\OtpDevice;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $to, string $content): array
    {
        $apiUrl = config('services.sms.url');
        $apiKey = config('services.sms.key');

        // Lấy nhà mạng của người nhận
        $network = $this->getNetwork($to);

        //Nếu là mạng Viettel thì loại trừ số điện thoại +84865941824
        if (in_array($network, ['VIETTEL', 'VIETNAMMOBILE', 'GMOBILE'])) {
            $froms = OtpDevice::query()
                ->where('is_active', true)
                ->whereNotNull('phone_number')
                ->where('phone_number', '!=', OtpDevice::EXCEPT_VIETTEL_PHONE)
                ->pluck('phone_number')
                ->toArray();
        } else {
            //Nếu là nhà mạng khác Viettel thì gửi bằng EXCEPT_VIETTEL_PHONE
            $froms = [OtpDevice::EXCEPT_VIETTEL_PHONE];
        }

        if (empty($froms)) {
            $froms = OtpDevice::query()
                ->where('is_active', true)
                ->whereNotNull('phone_number')
                ->pluck('phone_number')
                ->toArray();
            Log::info('List of froms:', $froms);
        }

        $from = Arr::random($froms);

        try {
            $response = Http::withHeaders([
                'x-api-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, [
                'content'   => $content,
                'encrypted' => false,
                'from'      => $from,
                'to'        => $to,
            ]);

            if (!$response->successful()) {
                Log::warning('SmsService: send failed', [
                    'to' => $to,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("SMS sending failed ({$response->status()}): " . $response->body());
            }

            Log::info('SmsService: sent', ['to' => $to, 'content' => $content]);

            return [
                'ok' => true,
                'status' => $response->status(),
                'body' => $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error('SmsService: exception', ['to' => $to, 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    private function getNetwork(string $phone): string
    {
        // Normalize phone number
        $phone = preg_replace('/^\+?84/', '0', $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) < 10) {
            return 'UNKNOWN';
        }

        $prefix = substr($phone, 0, 3);

        $viettel       = ['086', '096', '097', '098', '032', '033', '034', '035', '036', '037', '038', '039'];
        $vinaphone     = ['088', '091', '094', '083', '084', '085', '081', '082'];
        $mobifone      = ['089', '090', '093', '070', '079', '077', '076', '078'];
        $vietnammobile = ['052', '056', '058', '092'];
        $gmobile       = ['059', '099'];

        if (in_array($prefix, $viettel))       return 'VIETTEL';
        if (in_array($prefix, $vinaphone))     return 'VINAPHONE';
        if (in_array($prefix, $mobifone))      return 'MOBIFONE';
        if (in_array($prefix, $vietnammobile)) return 'VIETNAMMOBILE';
        if (in_array($prefix, $gmobile))       return 'GMOBILE';

        return 'UNKNOWN';
    }
}
