<?php

namespace App\Containers\AdminSection\House\Actions;

use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

final class UpdateHouseAction extends ParentAction
{
    public function run($data, $houseId)
    {
        $house = House::findOrFail($houseId);

        $fillable = ['name', 'address', 'district_id', 'is_active'];

        if (isset($data['guide_video']) && $data['guide_video'] instanceof UploadedFile) {
            $s3Helper = app(S3Helper::class);
            $s3Result = $s3Helper->uploadFile($data['guide_video'], 'house-videos/' . $house->id);
            if ($s3Result['success']) {
                $house->guide_video = $s3Result['file_path'];
            }
        } elseif (isset($data['guide_video']) && is_string($data['guide_video'])) {
            $house->guide_video = $data['guide_video'];
        }

        $house->fill(Arr::only($data, $fillable));
        $house->save();

        $roomUpdate = Arr::only($data, ['address', 'district_id']);

        if (!empty($roomUpdate)) {
            $house->rooms()->update($roomUpdate);
        }
        $hasCheckin = array_key_exists('checkin_instruction', $data);

        if ($hasCheckin) {
            $house->load([
                'rooms.checkinMethods',
            ]);

            foreach ($house->rooms as $room) {

                if ($hasCheckin) {
                    $checkinMethod = $room->checkinMethods->first();

                    if ($checkinMethod) {
                        $room->checkinMethods()->updateExistingPivot(
                            $checkinMethod->id,
                            ['way_to_house_message' => $data['checkin_instruction']]
                        );
                    }
                }

            }
        }

        $house->load(['rooms.checkinMethods']);

        return $house;
    }
}
