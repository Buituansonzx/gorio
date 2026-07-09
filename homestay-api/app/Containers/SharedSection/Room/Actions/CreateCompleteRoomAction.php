<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Tasks\CreateRoomTask;
use App\Containers\SharedSection\Room\Tasks\CreateRoomAttributeTask;
use App\Containers\SharedSection\Room\Tasks\CreateRoomSurroundingFacilityTask;
use App\Containers\SharedSection\Room\Tasks\CreateRoomImageTask;
use App\Containers\SharedSection\Room\Tasks\CreateRoomPricingPolicyTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class CreateCompleteRoomAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomTask $createRoomTask,
        private readonly CreateRoomAttributeTask $createRoomAttributeTask,
        private readonly CreateRoomSurroundingFacilityTask $createRoomSurroundingFacilityTask,
        private readonly CreateRoomImageTask $createRoomImageTask,
        private readonly CreateRoomPricingPolicyTask $createRoomPricingPolicyTask,
    ) {
    }

    public function run(array $data): Room
    {
        return DB::transaction(function () use ($data) {
            // Create the main room
            $roomData = [
                'room_type_id' => $data['room_type_id'],
                'room_access_type_id' => $data['room_access_type_id'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'area' => $data['area'] ?? null,
                'capacity' => $data['capacity'] ?? null,
                'bed_count' => $data['bed_count'] ?? null,
                'bathroom_count' => $data['bathroom_count'] ?? null,
                'floor' => $data['floor'] ?? null,
                'view_direction' => $data['view_direction'] ?? null,
                'base_price' => $data['base_price'],
                'cleaning_fee' => $data['cleaning_fee'] ?? null,
                'status' => $data['status'] ?? 'active',
                'check_in_time' => $data['check_in_time'] ?? null,
                'check_out_time' => $data['check_out_time'] ?? null,
                'min_stay_duration' => $data['min_stay_duration'] ?? null,
                'max_stay_duration' => $data['max_stay_duration'] ?? null,
                'advance_booking_days' => $data['advance_booking_days'] ?? null,
                'instant_booking' => $data['instant_booking'] ?? false,
                'smoking_allowed' => $data['smoking_allowed'] ?? false,
                'pets_allowed' => $data['pets_allowed'] ?? false,
                'parties_allowed' => $data['parties_allowed'] ?? false,
            ];

            $room = $this->createRoomTask->run($roomData);

            // Add room attributes if provided
            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $attributeData) {
                    $this->createRoomAttributeTask->run([
                        'room_id' => $room->id,
                        'attribute_id' => $attributeData['attribute_id'],
                        'value' => $attributeData['value'] ?? null,
                    ]);
                }
            }

            // Add surrounding facilities if provided
            if (!empty($data['surrounding_facilities'])) {
                foreach ($data['surrounding_facilities'] as $facilityData) {
                    $this->createRoomSurroundingFacilityTask->run([
                        'room_id' => $room->id,
                        'surrounding_facility_id' => $facilityData['surrounding_facility_id'],
                        'distance' => $facilityData['distance'] ?? null,
                        'unit' => $facilityData['unit'] ?? 'km',
                    ]);
                }
            }

            // Add room images if provided
            if (!empty($data['images'])) {
                foreach ($data['images'] as $imageData) {
                    $this->createRoomImageTask->run([
                        'room_id' => $room->id,
                        'image_url' => $imageData['image_url'],
                        'alt_text' => $imageData['alt_text'] ?? null,
                        'is_primary' => $imageData['is_primary'] ?? false,
                        'display_order' => $imageData['display_order'] ?? 0,
                    ]);
                }
            }

            // Add pricing policy if provided
            if (!empty($data['pricing_policy'])) {
                $this->createRoomPricingPolicyTask->run([
                    'room_id' => $room->id,
                    'policy_name' => $data['pricing_policy']['policy_name'],
                    'description' => $data['pricing_policy']['description'] ?? null,
                    'is_active' => $data['pricing_policy']['is_active'] ?? true,
                ]);
            }

            return $room->fresh(['roomType', 'roomAccessType', 'attributes', 'surroundingFacilities', 'images', 'pricingPolicies']);
        });
    }
}
