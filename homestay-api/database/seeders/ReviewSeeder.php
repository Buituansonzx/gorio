<?php

namespace Database\Seeders;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Review;
use App\Containers\SharedSection\Room\Models\Room;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('vi_VN');
        $hosts = Host::pluck('user_id')->toArray();
        $users = User::whereDate('created_at', '<', '2025-12-07')
            ->whereNotIn('id', $hosts)
            ->pluck('id')
            ->toArray();
        $rooms = Room::whereDoesntHave('orders.review')
            ->pluck('id')
            ->toArray();

        $seqByDay = [];
        foreach ($rooms as $roomId) {
            $hasReview = Review::whereHas('order', function($query) use ($roomId) {
                $query->where('room_id', $roomId);
            })->exists();
            if ($hasReview) {
                // Nếu đã có review, bỏ qua phòng này
                continue;
            }

            $reviewCount = rand(5, 10);

            for ($i = 0; $i < $reviewCount; $i++) {
                $userId = $users[array_rand($users)];

                $checkIn = Carbon::now()->subDays(rand(5, 30))
                    ->setTime(rand(12, 20), 0, 0);

                $checkOut = (clone $checkIn)->addHours(rand(1, 48));

                $expiresAt = (clone $checkIn)->addDay();

                $today = Carbon::now()->format('Ymd');
                if (!isset($seqByDay[$today])) {
                    $seqByDay[$today] = 0;
                }
                $seqByDay[$today]++;
                $sequence = str_pad($seqByDay[$today], 4, '0', STR_PAD_LEFT);

                $code = "BKS{$today}{$sequence}";
                $contents = [
                    'Tốt sạch đẹp','Phòng khá xinh và có ánh sáng rất dễ chịu. Không gia không quá nhỏ, phù hợp để healing','Phòng sạch, thơm, decor xinh xỉu luôn. 10/10 cho trải nghiệm này','Phòng xinh nha, gọn gàng sạch sẽ, có tủ lạnh mini mà trc đó mình k hề kì vọng có, qly home nhiệt tình','Phòng rất oke ạ, mình đã book nhiều lần đều ưng','Phòng đẹp, sạch sẽ và thơm tho, ánh sáng tốt và yên tĩnh. Đây đã là lần t2 mình book và sẽ tiếp tục ủng hộ',' không kì vọng nhiều khi book phòng 2ngay 1 đêm với giá 300k nhưng thật sự bất ngờ về chất lượng ở đây, mình sẽ quay lại lần sau','Nhận phòng rất ưng hết sức với mong đợi Giá hạt rẻ Phòng chill cực kì mỗi tội máy chiếu hơi khó bấm Nói chung rất okela','Phòng sạch đẹp thơm tho, chị chủ nhiệt tình hỗ trợ. Quay lại lần 2 vẫn thích ạ','Phòng mới, đầy đủ trang thiết bị, cozyy. Tổng thể rất oke ạ','tất cả đều rất tuyệt vờii, mỗi tội đặt đồ ăn trên app địa chỉ hơi quằn, chắc chắn sẽ có lần sau ạaa🫰🏻','Phòng siêu thơm, sạch sẽ và chỉnh chu, thêm nữa là sự nhiệt tình của chị chủ đối với khách hàng khiến khách lưu luyến rồiii nha chị😻Tháng sau sẽ quay lại ạ','home mới nên đồ cũng mới luôn , sạch sẽ , rộng rãi và rất thoáng nữa ạ 🥰🥰 hứa sẽ quay lại nữa ạ','Mình cần đặt gấp home lúc 5h sáng check in luôn mà thấy ít lượt đặt nên cũng lo không chỉn chu nhưng mà phòng ok, gọn gàng sạch sẽ, giống như ảnh chủ up ạ. Hướng dẫn check in siêu dễ, giá cả hợp lý, cơ sở vật chất đầy đủ và trông khá mới. Ncl ưng mng ạa','Từ A-Z đều ưng, recommend 100% 🤝','Phòng đẹp, ấm cúng, mọi thứ nói chung đều ok nhưng bồn tắm thoát nước chậm, mong sẽ có thêm thảm lót chân ở khu vực bồn tắm để sạch sẽ hơn','Checkout trễ vẫn hỗ trợ quá dễ thương, bếp đầu tư thêm nhiều gia vị nữa là đạt home nhé, mỗi gia vị cơ bản nên bọn em phải mua thêm nói chung .. siêu cấp vũ trụ','Phòng sạch sẽ và tiện nghi, rộng rãi. Giá cũng ổn.','ok phết, decor siu xinh','Chủ nhà nhiệt tình, mình thấy chỉ dẫn tự checkin rất dễ hiểu, phòng đẹp, quan trọng là rất sạch sẽ. Đầy đủ các tiện ích, 1 nơi đáng để quay lại cho những lần tiếp theo! Đánh giá 5 sao','Phòng đẹp, thơm tho, sạch sẽ, giá ổn.','phòng okela sạch sẽ, chủ nhà nice, ncl okela mỗi tội hôm đến là có bên cạnh đang xây nhà nên hơi ồn lúc chiều tí','home siuuu xinhh, là home mình ưng nhất từ trước đến giờ. phònggg mới rất sạch sẽ, đầy đủ tiên nghi, chủ nhà nhiệt tình nữa. rất high recommend cho mngg nhaa.','Phòng nhỏ xinh OK ạ, chị chủ nhiệt tình','home xinh lắm ạ sạch sẽ thoáng mát không điểm gì để chê 🫶🫶','Phòng sạch sẽ, dễ tìm. Vào phòng thơm lắm, rộng nữa. Nhưng mà máy chiếu hơi mờ, xem phim hơi khó ạ.','Phòng okela , decor xinh , máy chiếu xem phim như rạp tiện ghê,mình quên đồ cũng có a chủ nhiệt tình hỗ trợ giữ đồ giúp trước giờ checkin nè.','🤩tổng quan okee','Home đẹp, sẽ quay lại','goodddddddđf','Phòng sạch sẽ và ok lắm nha. Địa chỉ cũng dễ tìm nữa','Homestay hỗ trợ siêu nhiệt tình, phòng ổn trong tầm giá nha mn.','Nhìn chung thì phòng sạch sẽ, giống hình, chăn ga gối khăn tắm thơm tho nhíe. đọc đánh giá như đi khui secret vậy nhưng trộm vía ổnn.','Mọi thứ của home mình đều hài lònggggg','Phòng ok ạ!','Phòng mới lắm mọi người ơi, sạch sẽ thoáng mát giá cả phải chăng chị chủ siêu nhiệt tình. Sẽ ủng hộ lại trong thời gian tới','Một trong những home ưng nhất. Ủng hộ nha','Home rất rộng so với nhiều home khác mình từng book. Phòng decor xinh, sạch sẽ','Quá oke','Phòng rộng rãi thoáng nhiều anh sáng, trung tâm tiện đi lại','ok nha quay lại dài dài','phòng rộng, bếp siu toaa💗💗 điểm cộng lớn nhất lun, 🥹 phòng sạch sẽ khi mình đến, đầy đủ đồ dùng, sẽ ghé ủng hộ home tíep ạ😋','Phòng nhỏ mà ấm cúng, đầy đủ tiện nghi. Sẽ quay lại 😉😉','Chủ nhà nhiệt tình, phòng sạch sẽ, thơm tho, ưng cái ghế sofa dài. Sẽ quay lại nếu có dịp 😘','sạch đẹp dễ dàng checkin','Rất là tốt luôn','Phòng oki, sạch sẽ, thoải mái','home xinh lắm ạ sạch sẽ thoáng mát không điểm gì để chê 🫶🫶','Home khá ổn, chủ home thân thiện, hỗ trợ nhiệt tình','Dạ okela lém lun ớ','Hợp lý và chill cho cặp đôi thích riêng tư','phòng đẹp giá cả qá là ok','view đẹp, phòng đẹp','Overall oke nha mn nên trải nghiệm 👍vị trí trung tâm đi đâu cũng dễ, ngay gần cơ quan mình bay ra làm giấy tờ nên tiện book. phòng khá xinh sạch sẽ điều hoà lạnh cóng lun:))))) Host support rất nhiệt tình lịch sự dth View ban công đẹp 👎','Ok nhes bro oi','Phòng siêuu xinhhh, siêu đầy đủ nhaa. Anh chủ nhiệt tìnhh cute lắm ạaa. Chắc chắn sẽ quay lại ạ💗','phòng xinh, có cửa sát giường mở ra cũng thoáng','Eo ơi phòng siêu đẹp nhé🥺. Sạch sẽ, chăn ga mùi thơm nhẹ, phòng cũng siêu thoáng. Mình rất thích cách decor như vậy. Nói chung là ưng lắm ý , sẽ quay lại với home nhiều lần ạaaa🥰😋😋','Phòng đẹp, sạch sẽ, đầy đủ tiện nghi'
                ];

                $order = Order::create([
                    'user_id' => $userId,
                    'room_id' => $roomId,
                    'check_in' => $checkIn->toDateTimeString(),
                    'check_out' => $checkOut->toDateTimeString(),
                    'number_of_guests' => rand(1, 4),
                    'guest_name' => $faker->name(),
                    'guest_phone' => '0' . random_int(100000000, 999999999),
                    'total' => rand(500000, 5000000),
                    'note' => $faker->sentence(),
                    'code' => $code,
                    'status' => 'paid',
                    'expires_at' => $expiresAt->toDateTimeString(),
                ]);

                Review::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'rating' => 5,
                    'content' => $contents[array_rand($contents)],
                    'cleanliness_rating' => rand(4, 5),
                    'accuracy_rating' => rand(4, 5),
                    'checkin_rating' => rand(4, 5),
                    'communication_rating' => rand(4, 5),
                    'location_rating' => rand(4, 5),
                    'value_rating' => rand(4, 5),
                ]);
            }
        }
    }
}
