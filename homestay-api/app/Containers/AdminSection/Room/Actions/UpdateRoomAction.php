<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Services\ImageService;

final class UpdateRoomAction extends ParentAction
{
    public function run($id, $data)
    {
        $room = Room::find($id);
        if(!empty($data['name'])) {
            $room->name = $data['name'];
        }
        if(!empty($data['title'])) {
            $room->title = $data['title'];
        }
        if (isset($data['is_active'])) {
            if ($data['is_active'] !== '') {
                $room->is_active = (int)$data['is_active'];
            }
        }
        if(!empty($data['checkin_instruction'])) {
            $room->checkinMethods->first()->pivot->way_to_house_message = $data['checkin_instruction'];
            $room->checkinMethods->first()->pivot->save();
        }
        $room->save();

        if(!empty($data['file'])){
            $file = $data['file'];

            $currentCover = $room->medias()
                ->where('is_cover', true)
                ->first();

            $media = app(ImageService::class)->upload(
                $file,
                "room-images/{$room->id}",
                's3',
                [
                    'room_id' => $room->id,
                    'index' => 0,
                ]
            );
            if ($currentCover) {
                //Nếu đã có cover, update các field path/url/variants của bản ghi cũ
                $currentCover->update([
                    'disk' => $media->disk,
                    'path' => $media->path,
                    'width' => $media->width,
                    'height' => $media->height,
                    'mime' => $media->mime,
                    'variants' => $media->variants,
                ]);

                //Xóa bản ghi mới vừa tạo để không duplicate
                $media->delete();
            } else {
                $livingRoomId = RoomImageGroup::where('code', RoomImageGroup::CODE_LIVING_ROOM)->first()->id;
                $media->room_image_area_group_id = $livingRoomId;
                $media->is_cover = true;

                //Nếu chưa có cover, giữ bản ghi mới
                $room->medias()->save($media);
            }
        }
        $coverMedia = $room->medias()->where('is_cover', true)->select('path')->first();

        if ($coverMedia) {
            // Thêm URL trực tiếp dùng S3Helper
            $coverMedia->url = S3Helper::getS3ImageUrl($coverMedia->path);
        }

        $room->cover_media = $coverMedia;
        return $room;
    }
}
