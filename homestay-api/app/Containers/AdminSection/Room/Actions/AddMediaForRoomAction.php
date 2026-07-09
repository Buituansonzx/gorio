<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Services\ImageService;

final class AddMediaForRoomAction extends ParentAction
{
    public function __construct(private readonly ImageService $imageService)
    {
    }
    public function run($roomId, $data)
    {
        $files = $data['items'] ?? [];
        $medias = [];

        foreach ($files as $file) {
            $areaGroupId = $file['area_group_id'] ?? null;
             $media =$this->imageService->upload($file['media'], "room-images/{$roomId}", 's3', [
                'room_id' => $roomId,
                'room_image_area_group_id' => $areaGroupId,
                'index' => 0,
            ]);
            $medias[] = $media;
        }

        return $medias;
    }
}
