<?php
namespace App\Containers\SharedSection\Order\Services;
use Illuminate\Support\Facades\Http;

class VietQRService
{
    public function generate(array $data): array
    {
        $vietQRConfig = explode(",", env("VIETQR_CONFIG"));

        $response = Http::post(config('services.vietqr.base_url') . '/v2/generate', [
            'accountNo'   => $vietQRConfig[0],
            'accountName' => $vietQRConfig[1],
            'acqId'       => $vietQRConfig[2],
            'amount'      => $data['total'],
            'addInfo'     => $data['code'],
            'template'    => 'qr_only',
        ]);
        return $response->json();
    }
}
