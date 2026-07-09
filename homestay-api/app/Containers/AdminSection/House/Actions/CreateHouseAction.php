<?php

namespace App\Containers\AdminSection\House\Actions;

use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Helpers\S3Helper;
use Illuminate\Http\UploadedFile;

final class CreateHouseAction extends ParentAction
{
    public function run($data)
    {
        $houseData = [
            'name' => $data['name'],
            'address' => $data['address'],
            'district_id' => $data['district_id'],
            'host_id' => $data['host_id'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ];

        if (isset($data['guide_video']) && $data['guide_video'] instanceof UploadedFile) {
            $s3Helper = app(S3Helper::class);
            $s3Result = $s3Helper->uploadFile($data['guide_video'], 'house-videos');
            if ($s3Result['success']) {
                $houseData['guide_video'] = $s3Result['file_path'];
            }
        } elseif (isset($data['guide_video']) && is_string($data['guide_video'])) {
            $houseData['guide_video'] = $data['guide_video'];
        }

        $house = House::create($houseData);
        return $house;
    }
}
