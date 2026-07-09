<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\Attribute;
use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Containers\SharedSection\Room\Models\District;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\House;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomAccessType;
use App\Containers\SharedSection\Room\Models\RoomAttribute;
use App\Containers\SharedSection\Room\Models\RoomCheckinInstruction;
use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Containers\SharedSection\Room\Models\RoomSurroundingFacility;
use App\Containers\SharedSection\Room\Models\RoomType;
use App\Containers\SharedSection\Room\Models\SurroundingFacility;
use App\Containers\SharedSection\Room\Models\View;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($files)
    {
        foreach ($files as $file) {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload.');
            }
            $rows = Excel::toArray([], $file)[0];
            unset($rows[0]);
                foreach ($rows as $row) {
                    $houseName = trim($row[0] ?? null);             // Tên của homestay
                    $titleRoom = trim($row[1] ?? null);             // Tiêu đề của phòng
                    $name = trim($row[2] ?? null);                  // Tên của phòng
                    $roomTypeCode = trim($row[3] ?? null);          // Mã loại phòng
                    $maxGuests = trim($row[4] ?? null);             // Số lượng khách tối đa
                    $area = trim($row[5] ?? null);                  // Diện tích phòng
                    $description = trim($row[6] ?? null);           // Mô tả phòng
                    $numBedrooms = trim($row[7] ?? null);          // Số lượng phòng ngủ
                    $numLivings = trim($row[8] ?? null);           // Số lượng phòng khách
                    $numExtraMattresses = trim($row[9] ?? null);   // Số lượng đệm phụ
                    $numSharedBathrooms = trim($row[10] ?? null);   // Số lượng phòng tắm chung
                    $numKitchens = trim($row[11] ?? null);          // Số lượng bếp
                    $viewCodes = trim($row[12] ?? null);            // Views
                    $amenityCodes = trim($row[13] ?? null);         // Tiện nghi
                    $checkinMethodCode = trim($row[14] ?? null);    // Mã phương thức nhận phòng
                    $wifiName = trim($row[15] ?? null);
                    $wifiPassword = trim($row[16] ?? null);
                    $wayToHouse = trim($row[17] ?? null);           // Cách vào nhà
                    
                    if (!$houseName) {
                        continue;
                    }
                    $house = House::where('name', $houseName)->first();
                    if(!$house){
                        throw new \Exception("House with name {$houseName} not found.");
                    }
                    $roomTypeId = RoomType::where('code', $roomTypeCode)->value('id');
                    if (!$roomTypeId) {
                        throw new \Exception("Room type with code {$roomTypeCode} not found.");
                    }
                    $accessTypeId = RoomAccessType::where('code', 'full_private')->value('id');
                    
                    $districtId = $house->district_id;
                    $district = District::with('province')->find($districtId);

                    $province = $district?->province;

                    if (!$province) {
                        throw new \Exception("Province not found for district ID {$districtId}.");
                    }
                    $provinceCode = $province->code;
                    $hostId = $house->host_id;

                    $checkinMethod = CheckinMethod::where('code', $checkinMethodCode)->first();
                    $address = $house->address;
                    $latitude = $house->latitude;
                    $longitude = $house->longitude;
                    $room = Room::create([
                        'host_id' => $hostId,
                        'house_id' => $house->id,
                        'uid' => (string)Str::uuid(),
                        'district_id' => $districtId,
                        'name' => $name,
                        'title' => $titleRoom,
                        'room_type_id' => $roomTypeId,
                        'price_per_night' => 0,
                        'max_guests' => $maxGuests,
                        'num_beds' => 1,
                        'num_bathrooms' => 1,
                        'area_sqm' => $area,
                        'description' => $description,
                        'is_active' => true,
                        'access_type_id' => $accessTypeId,
                        'address' => $address,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);
                    $roomId = $room->id;
                    $code = "{$provinceCode}" . "H" . "{$hostId}" . "R" . "{$roomId}";
                    $room->code = $code;
                    $room->save();
                    $roomId = $room->id;
                    //Add Checkin Method
                    RoomCheckinInstruction::create([
                        'room_id' => $roomId,
                        'checkin_method_id' => $checkinMethod->id,
                        'way_to_house_message' => $wayToHouse,
                        'wifi_name' => $wifiName,
                        'wifi_password' => $wifiPassword,
                    ]);

                    //Add Attributes
                    $attributesMap = [
                        'bedroom' => $numBedrooms,
                        'living_room' => $numLivings,
                        'extra_mattress' => $numExtraMattresses,
                        'shared_bathroom' => $numSharedBathrooms,
                        'kitchen' => $numKitchens,
                        'bed' => 1,
                        'private_bathroom' => 1,
                    ];
                    foreach ($attributesMap as $code => $quantity) {
                        if (!$quantity || $quantity <= 0) {
                            continue;
                        }

                        $attributeId = Attribute::where('code', $code)->value('id');
                        if (!$attributeId) {
                            throw new \Exception("Attribute code {$code} not found.");
                        }

                        $room->attributes()->attach($attributeId, ['quantity' => $quantity]);
                    }


                    //Add Views
                    $viewCodeArray = array_map('trim', explode(',', $viewCodes));
                    foreach ($viewCodeArray as $code) {
                        $view = View::where('code', $code)->first();
                        if ($view) {
                            $room->views()->attach($view->id);
                        }
                    }

                    //Add Amenities
                    $amenityCodeArray = array_filter(array_map('trim', preg_split('/[,.]/', $amenityCodes)));
                    foreach ($amenityCodeArray as $code) {
                        $amenity = Amenity::where('code', $code)->first();
                        if ($amenity) {
                            $room->amenities()->attach($amenity->id);
                        } else {
                            throw new \Exception("Amenity with code {$code} not found.");
                        }
                    }
                }
        }
    }
}
