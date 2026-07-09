<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Amenity;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

final class AmenitiesSeeder extends Seeder
{
    public function run(): void
    {
        $bathRoom = DB::table('amenity_group')->where('code', 'bathroom')->first();
        $bedRoomLaundry = DB::table('amenity_group')->where('code', 'bedroom_laundry')->first();
        $entertainment = DB::table('amenity_group')->where('code', 'entertainment')->first();
        $family = DB::table('amenity_group')->where('code', 'family')->first();
        $coolingHeating = DB::table('amenity_group')->where('code', 'cooling_heating')->first();
        $safety = DB::table('amenity_group')->where('code', 'safety')->first();
        $internet = DB::table('amenity_group')->where('code', 'internet')->first();
        $kitchen = DB::table('amenity_group')->where('code', 'kitchen')->first();
        $locationFeature = DB::table('amenity_group')->where('code', 'location_feature')->first();
        $outdoor = DB::table('amenity_group')->where('code', 'outdoor')->first();
        $facilitiesParking = DB::table('amenity_group')->where('code', 'facilities_parking')->first();
        $services = DB::table('amenity_group')->where('code', 'services')->first();
        if (!empty($bathRoom) && !empty($bedRoomLaundry) && !empty($entertainment) && !empty($family) && !empty($coolingHeating) && !empty($safety) && !empty($internet) && !empty($kitchen) && !empty($locationFeature) && !empty($outdoor) && !empty($facilitiesParking) && !empty($services)) {
            $amenities = [
                ['code' => 'smart_toilet', 'name' => ['vi' => 'Bồn cầu vệ sinh Thông minh', 'en' => 'Smart toilet'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'bathtub', 'name' => ['vi' => 'Bồn tắm', 'en' => 'Bathtub'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'shampoo', 'name' => ['vi' => 'Dầu gội đầu', 'en' => 'Shampoo'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'conditioner', 'name' => ['vi' => 'Dầu xả', 'en' => 'Conditioner'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'hair_dryer', 'name' => ['vi' => 'Máy sấy tóc', 'en' => 'Hair dryer'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'hot_water', 'name' => ['vi' => 'Nước nóng', 'en' => 'Hot water'], 'is_basic' => true, 'group_id' => $bathRoom->id],
                ['code' => 'toiletries', 'name' => ['vi' => 'Sản phẩm vệ ính', 'en' => 'Toiletries'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'body_wash', 'name' => ['vi' => 'Sữa tắm', 'en' => 'Body wash'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'outdoor_shower', 'name' => ['vi' => 'Vòi sen tắm ngoài trời', 'en' => 'Outdoor shower'], 'is_basic' => false, 'group_id' => $bathRoom->id],
                ['code' => 'iron', 'name' => ['vi' => 'Bàn là', 'en' => 'Iron'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'bedding_set', 'name' => ['vi' => 'Bộ chăn ga gối', 'en' => 'Bedding set'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'clothes_rack', 'name' => ['vi' => 'Giá phơi quần áo', 'en' => 'Clothes rack'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'safe_box', 'name' => ['vi' => 'Két sắt', 'en' => 'Safe box'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'mosquito_net', 'name' => ['vi' => 'Màn chống muỗi', 'en' => 'Mosquito net'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'blackout_curtain', 'name' => ['vi' => 'Mành chắn sáng cho phòng', 'en' => 'Blackout curtain'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'washing_machine', 'name' => ['vi' => 'Máy giặt', 'en' => 'Washing machine'], 'is_basic' => true, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'dryer_machine', 'name' => ['vi' => 'Máy sấy quần áo', 'en' => 'Dryer machine'], 'is_basic' => true, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'hanger', 'name' => ['vi' => 'Móc treo quần áo', 'en' => 'Hanger'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'wardrobe', 'name' => ['vi' => 'Nơi để quần áo', 'en' => 'Wardrobe'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'extra_blanket', 'name' => ['vi' => 'Thêm chăn gối', 'en' => 'Extra blanket'], 'is_basic' => false, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'essentials', 'name' => ['vi' => 'Tiện nghi thiết yếu', 'en' => 'Essentials'], 'is_basic' => true, 'group_id' => $bedRoomLaundry->id],
                ['code' => 'billiard_table', 'name' => ['vi' => 'Bàn bi-da', 'en' => 'Billiard table'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'ping_pong_table', 'name' => ['vi' => 'Bàn bóng bàn', 'en' => 'Ping pong table'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'laser_tag', 'name' => ['vi' => 'Bắn súng laser', 'en' => 'Laser tag'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'sound_system', 'name' => ['vi' => 'Hệ thống âm thanh', 'en' => 'Sound system'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'net_enclosure', 'name' => ['vi' => 'Khung lưới bao sân', 'en' => 'Net enclosure'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'ethernet_connection', 'name' => ['vi' => 'Kết nối Ethernet', 'en' => 'Ethernet connection'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'indoor_climbing', 'name' => ['vi' => 'Leo núi trong nhà', 'en' => 'Indoor climbing'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'arcade_machine', 'name' => ['vi' => 'Máy chơi game arcade', 'en' => 'Arcade machine'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'game_console', 'name' => ['vi' => 'Máy chơi điện tử', 'en' => 'Game console'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'record_player', 'name' => ['vi' => 'Máy quay đĩa', 'en' => 'Record player'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'themed_room', 'name' => ['vi' => 'Phòng theo chủ đề', 'en' => 'Themed room'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'home_cinema', 'name' => ['vi' => 'Rạp chiếu phim', 'en' => 'Home cinema'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'bowling_lane', 'name' => ['vi' => 'Sàn bowling', 'en' => 'Bowling lane'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'books', 'name' => ['vi' => 'Sách và ấn phẩm để đọc', 'en' => 'Books'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'mini_golf', 'name' => ['vi' => 'Sân golf mini', 'en' => 'Mini golf'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'fitness_equipment', 'name' => ['vi' => 'Thiết bị tập thể dục', 'en' => 'Fitness equipment'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'giant_games', 'name' => ['vi' => 'Trò chơi kích thước lớn', 'en' => 'Giant games'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'tv', 'name' => ['vi' => 'TV', 'en' => 'TV'], 'is_basic' => true, 'group_id' => $entertainment->id],
                ['code' => 'projector', 'name' => ['vi' => 'Máy chiếu', 'en' => 'Projector',], 'is_basic' => false, 'group_id' => $entertainment->id,],
                ['code' => 'piano', 'name' => ['vi' => 'Đàn piano', 'en' => 'Piano'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'skateboard_ramp', 'name' => ['vi' => 'Đường dốc trượt ván', 'en' => 'Skateboard ramp'], 'is_basic' => false, 'group_id' => $entertainment->id],
                ['code' => 'baby_changing_table', 'name' => ['vi' => 'Bàn thay tã, bỉm cho em bé', 'en' => 'Baby changing table'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'baby_bathtub', 'name' => ['vi' => 'Bồn tắm cho em bé', 'en' => 'Baby bathtub'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'kids_dining_set', 'name' => ['vi' => 'Bộ dụng cụ ăn uống cho trẻ em', 'en' => 'Kids dining set'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'window_guards', 'name' => ['vi' => 'Chấn song cửa sổ', 'en' => 'Window guards'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'board_games', 'name' => ['vi' => 'Các trò chơi với bảng/bàn cờ', 'en' => 'Board games'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'crib', 'name' => ['vi' => 'Cũi', 'en' => 'Crib'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'travel_crib', 'name' => ['vi' => 'Cũi Pack ’n Play/cũi du lịch', 'en' => 'Travel crib'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'high_chair', 'name' => ['vi' => 'Ghế ăn cho trẻ em', 'en' => 'High chair'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'fireplace_guard', 'name' => ['vi' => 'Lưới chắn lò sưởi', 'en' => 'Fireplace guard'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'corner_protectors', 'name' => ['vi' => 'Miếng bịt góc bàn', 'en' => 'Corner protectors'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'kids_playroom', 'name' => ['vi' => 'Phòng chơi cho trẻ em', 'en' => 'Kids playroom'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'baby_safety_gate', 'name' => ['vi' => 'Rào chắn an toàn cho em bé', 'en' => 'Baby safety gate'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'kids_books_and_toys', 'name' => ['vi' => 'Sách và đồ chơi trẻ em', 'en' => 'Kids books and toys'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'outdoor_play_area', 'name' => ['vi' => 'Sân chơi ngoài trời', 'en' => 'Outdoor play area'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'baby_monitor', 'name' => ['vi' => 'Thiết bị giám sát trẻ em', 'en' => 'Baby monitor'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'kids_bicycle', 'name' => ['vi' => 'Xe đạp trẻ em', 'en' => 'Kids bicycle'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'babysitting_recommend', 'name' => ['vi' => 'Đề xuất người trông trẻ', 'en' => 'Babysitting recommend'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'outlet_covers', 'name' => ['vi' => 'Đồ bịt ổ điện', 'en' => 'Outlet covers'], 'is_basic' => false, 'group_id' => $family->id],
                ['code' => 'heating_system', 'name' => ['vi' => 'Hệ thống sưởi', 'en' => 'Heating system'], 'is_basic' => false, 'group_id' => $coolingHeating->id],
                ['code' => 'indoor_fireplace', 'name' => ['vi' => 'Lò sưởi trong nhà', 'en' => 'Indoor fireplace'], 'is_basic' => false, 'group_id' => $coolingHeating->id],
                ['code' => 'handheld_fan', 'name' => ['vi' => 'Quạt cầm tay', 'en' => 'Handheld fan'], 'is_basic' => false, 'group_id' => $coolingHeating->id],
                ['code' => 'ceiling_fan', 'name' => ['vi' => 'Quạt trần', 'en' => 'Ceiling fan'], 'is_basic' => false, 'group_id' => $coolingHeating->id],
                ['code' => 'air_conditioner', 'name' => ['vi' => 'Điều hòa nhiệt độ', 'en' => 'Air conditioner'], 'is_basic' => true, 'group_id' => $coolingHeating->id],

                ['code' => 'fire_extinguisher', 'name' => ['vi' => 'Bình chữa cháy', 'en' => 'Fire extinguisher'], 'is_basic' => false, 'group_id' => $safety->id],
                ['code' => 'first_aid_kit', 'name' => ['vi' => 'Bộ sơ cứu', 'en' => 'First aid kit'], 'is_basic' => false, 'group_id' => $safety->id],
                ['code' => 'smoke_detector', 'name' => ['vi' => 'Máy báo khói', 'en' => 'Smoke detector'], 'is_basic' => false, 'group_id' => $safety->id],
                ['code' => 'co_detector', 'name' => ['vi' => 'Máy phát hiện khí CO', 'en' => 'CO detector'], 'is_basic' => false, 'group_id' => $safety->id],

                ['code' => 'pocket_wifi', 'name' => ['vi' => 'Bộ phát Wi-fi bỏ túi', 'en' => 'Pocket Wi-Fi'], 'is_basic' => false,'group_id' => $internet->id],
                ['code' => 'dedicated_workspace', 'name' => ['vi' => 'Không gian riêng để làm việc', 'en' => 'Dedicated workspace'], 'is_basic' => false,'group_id' => $internet->id],
                ['code' => 'wifi', 'name' => ['vi' => 'Wi-fi', 'en' => 'Wi-Fi'], 'is_basic' => true,'group_id' => $internet->id],

                ['code' => 'dining_table', 'name' => ['vi' => 'Bàn ăn', 'en' => 'Dining table'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'dishes_silverware', 'name' => ['vi' => 'Bát đĩa và đồ bạc', 'en' => 'Dishes and silverware'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'kitchen', 'name' => ['vi' => 'Bếp', 'en' => 'Kitchen'], 'is_basic' => true, 'group_id' => $kitchen->id],
                ['code' => 'kitchenette', 'name' => ['vi' => 'Bếp nhỏ', 'en' => 'Kitchenette'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'coffee', 'name' => ['vi' => 'Cà phê', 'en' => 'Coffee'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'baking_tray', 'name' => ['vi' => 'Khay nướng bánh', 'en' => 'Baking tray'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'wine_glasses', 'name' => ['vi' => 'Ly uống rượu vang', 'en' => 'Wine glasses'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'oven', 'name' => ['vi' => 'Lò nướng', 'en' => 'Oven'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'microwave', 'name' => ['vi' => 'Lò vi sóng', 'en' => 'Microwave'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'bread_maker', 'name' => ['vi' => 'Máy làm bánh mì', 'en' => 'Bread maker'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'toaster', 'name' => ['vi' => 'Máy nướng bánh mì', 'en' => 'Toaster'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'coffee_maker', 'name' => ['vi' => 'Máy pha cà phê', 'en' => 'Coffee maker'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'dishwasher', 'name' => ['vi' => 'Máy rửa chén bát', 'en' => 'Dishwasher'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'blender', 'name' => ['vi' => 'Máy xay sinh tố', 'en' => 'Blender'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'rice_cooker', 'name' => ['vi' => 'Nồi cơm điện', 'en' => 'Rice cooker'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'trash_compactor', 'name' => ['vi' => 'Thùng rác có chức năng nén rác', 'en' => 'Trash compactor'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'refrigerator', 'name' => ['vi' => 'Tủ lạnh', 'en' => 'Refrigerator'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'mini_fridge', 'name' => ['vi' => 'Tủ lạnh mini', 'en' => 'Mini fridge'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'freezer', 'name' => ['vi' => 'Tủ đông', 'en' => 'Freezer'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'bbq_utensils', 'name' => ['vi' => 'Đồ dùng nướng thịt ngoài trời', 'en' => 'BBQ utensils'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'basic_cooking_tools', 'name' => ['vi' => 'Đồ nấu ăn cơ bản', 'en' => 'Basic cooking tools'], 'is_basic' => false, 'group_id' => $kitchen->id],
                ['code' => 'electric_kettle', 'name' => ['vi' => 'Ấm đun nước nóng', 'en' => 'Electric kettle'], 'is_basic' => false, 'group_id' => $kitchen->id],

                ['code' => 'nearby_laundry', 'name' => ['vi' => 'Hiệu giặt ủi tự động gần đây', 'en' => 'Nearby laundry'], 'is_basic' => false,'group_id' => $locationFeature->id],
                ['code' => 'beach_access', 'name' => ['vi' => 'Lối ra bãi biển', 'en' => 'Beach access'], 'is_basic' => false, 'group_id' => $locationFeature->id],
                ['code' => 'lake_access', 'name' => ['vi' => 'Lối ra hồ', 'en' => 'Lake access'], 'is_basic' => false, 'group_id' => $locationFeature->id],
                ['code' => 'resort_entrance', 'name' => ['vi' => 'Lối vào khu nghỉ dưỡng', 'en' => 'Resort entrance'], 'is_basic' => false,'group_id' => $locationFeature->id],
                ['code' => 'private_entrance', 'name' => ['vi' => 'Lối vào riêng', 'en' => 'Private entrance'], 'is_basic' => false, 'group_id' => $locationFeature->id],
                ['code' => 'waterfront', 'name' => ['vi' => 'Ven sông/hồ/biển', 'en' => 'Waterfront'], 'is_basic' => false, 'group_id' => $locationFeature->id],
                ['code' => 'ski_in_ski_out', 'name' => ['vi' => 'Đường trượt tuyết thẳng tới cửa', 'en' => 'Ski-in/Ski-out'], 'is_basic' => false, 'group_id' => $locationFeature->id],

                ['code' => 'boat_dock', 'name' => ['vi' => 'Bến thuyền', 'en' => 'Boat dock'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'outdoor_kitchen', 'name' => ['vi' => 'Bếp ngoài trời', 'en' => 'Outdoor kitchen'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'fire_pit', 'name' => ['vi' => 'Bếp đốt lửa trại', 'en' => 'Fire pit'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'sun_loungers', 'name' => ['vi' => 'Ghế tắm nắng', 'en' => 'Sun loungers'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'outdoor_dining_area', 'name' => ['vi' => 'Khu vực ăn uống ngoài trời', 'en' => 'Outdoor dining area'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'bbq_grill', 'name' => ['vi' => 'Lò nướng BBQ', 'en' => 'BBQ grill'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'outdoor_furniture', 'name' => ['vi' => 'Nội thất ngoài trời', 'en' => 'Outdoor furniture'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'patio_or_balcony', 'name' => ['vi' => 'Sân hoặc ban công', 'en' => 'Patio or balcony'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'backyard', 'name' => ['vi' => 'Sân sau', 'en' => 'Backyard'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'kayak', 'name' => ['vi' => 'Thuyền Kayak', 'en' => 'Kayak'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'beach_gear', 'name' => ['vi' => 'Tiện nghi khi đi tắm biển', 'en' => 'Beach gear'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'hammock', 'name' => ['vi' => 'Võng', 'en' => 'Hammock'], 'is_basic' => false, 'group_id' => $outdoor->id],
                ['code' => 'bicycle', 'name' => ['vi' => 'Xe đạp', 'en' => 'Bicycle'], 'is_basic' => false, 'group_id' => $outdoor->id],

                ['code' => 'swimming_pool', 'name' => ['vi' => 'Bể bơi', 'en' => 'Swimming pool'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'hot_tub', 'name' => ['vi' => 'Bồn tắm nước nóng', 'en' => 'Hot tub'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'paid_parking_on_premises', 'name' => ['vi' => 'Chỗ đỗ xe có thu phí trong khuôn viên', 'en' => 'Paid parking on premises'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'free_parking_on_premises', 'name' => ['vi' => 'Chỗ đỗ xe miễn phí tại nơi ở', 'en' => 'Free parking on premises'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'paid_parking_off_premises', 'name' => ['vi' => 'Chỗ đỗ xe ngoài khuôn viên, có thu phí', 'en' => 'Paid parking off premises'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'free_street_parking', 'name' => ['vi' => 'Miễn phí đỗ xe trên đường/phố', 'en' => 'Free street parking'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'single_storey_home', 'name' => ['vi' => 'Nhà một tầng', 'en' => 'Single storey home'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'private_living_room', 'name' => ['vi' => 'Phòng khách riêng', 'en' => 'Private living room'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'gym', 'name' => ['vi' => 'Phòng tập thể hình', 'en' => 'Gym'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'sauna', 'name' => ['vi' => 'Phòng xông hơi khô', 'en' => 'Sauna'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'hockey_rink', 'name' => ['vi' => 'Sân khúc côn cầu', 'en' => 'Hockey rink'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'elevator', 'name' => ['vi' => 'Thang máy', 'en' => 'Elevator'], 'is_basic' => false,'group_id' => $facilitiesParking->id],
                ['code' => 'ev_charger', 'name' => ['vi' => 'Thiết bị sạc xe điện', 'en' => 'EV charger'], 'is_basic' => false,'group_id' => $facilitiesParking->id],

                ['code' => 'breakfast', 'name' => ['vi' => 'Bữa sáng', 'en' => 'Breakfast'], 'is_basic' => false, 'group_id'=>$services->id],
                ['code' => 'luggage_dropoff', 'name' => ['vi' => 'Cho phép gửi hành lý', 'en' => 'Luggage dropoff allowed'], 'is_basic' => false, 'group_id'=>$services->id],
                ['code' => 'long_term_stays', 'name' => ['vi' => 'Cho phép ở dài hạn', 'en' => 'Long term stays allowed'], 'is_basic' => false, 'group_id'=>$services->id],
                ['code' => 'cleaning_during_stay', 'name' => ['vi' => 'Có dịch vụ dọn vệ sinh trong thời gian ở', 'en' => 'Cleaning during stay'], 'is_basic' => false, 'group_id'=>$services->id],
                ['code' => 'duplex', 'name' => ['vi' => 'Căn hộ thông tầng', 'en' => 'Duplex'], 'is_basic' => false, 'group_id'=>$services->id],

            ];

            foreach ($amenities as $amenity) {
                $exists = DB::table('amenities')->where('code', $amenity['code'])->exists();

                if (!$exists) {
                    Amenity::create([
                        'code'     => $amenity['code'],
                        'name'     => $amenity['name'],
                        'amenity_group_id' => $amenity['group_id'] ?? null,
                        'is_basic' => $amenity['is_basic'],
                    ]);
                }
            }
        }

    }
}
