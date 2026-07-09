<?php

namespace App\Console\Commands;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\House;
use App\Containers\SharedSection\Room\Models\Review;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SeedReviewsFromJsonCommand extends Command
{
    protected $signature = 'app:seed-reviews-from-json';

    protected $description = 'Tạo user + order paid (trong quá khứ) + review từ file JSON cho tất cả room của 1 house';

    // Điền HOUSE_ID rồi chạy command. Tất cả rooms của house sẽ được seed REVIEWS_PER_ROOM reviews.
    private const HOUSE_ID = '019daf58-d420-701b-876b-9c0c66d33b65';

    private const JSON_PATH = 'database/data/reviews/reviews_nha_so_1.json';

    private const REVIEWS_PER_ROOM = 10;

    public function handle(): int
    {
        $houseId = self::HOUSE_ID;
        if (empty($houseId)) {
            $this->error('Chưa điền HOUSE_ID trong SeedReviewsFromJsonCommand.');
            return self::FAILURE;
        }

        $jsonPath = base_path(self::JSON_PATH);
        if (!file_exists($jsonPath)) {
            $this->error("Không tìm thấy file JSON: {$jsonPath}");
            return self::FAILURE;
        }

        $reviews = json_decode(file_get_contents($jsonPath), true);
        if (!is_array($reviews) || empty($reviews)) {
            $this->error('File JSON không hợp lệ hoặc rỗng.');
            return self::FAILURE;
        }

        $house = House::with('rooms')->find($houseId);
        if (!$house) {
            $this->error("Không tìm thấy house với ID: {$houseId}");
            return self::FAILURE;
        }

        $rooms = $house->rooms;
        if ($rooms->isEmpty()) {
            $this->error("House {$houseId} không có room nào.");
            return self::FAILURE;
        }

        $this->info("House: {$house->id} — {$rooms->count()} rooms, mỗi room " . self::REVIEWS_PER_ROOM . " reviews");

        $faker = Factory::create('vi_VN');
        $now = now();
        $seqByDay = [];
        $totalReviews = $rooms->count() * self::REVIEWS_PER_ROOM;

        $bar = $this->output->createProgressBar($totalReviews);
        $bar->start();

        foreach ($rooms as $room) {
            $pickedReviews = $this->pickReviews($reviews, self::REVIEWS_PER_ROOM);

            foreach ($pickedReviews as $item) {
                $name = $this->generateNameWithoutHonorific($faker);
                $nameParts = explode(' ', $name);
                $firstName = end($nameParts);
                $lastName = $nameParts[0];

                $phone = $this->generateUniquePhone();
                $email = $this->generateUniqueEmail($faker);

                $user = User::create([
                    'name' => $name,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'country_code' => 'VN',
                    'phone_code' => '84',
                    'phone_number' => $phone,
                    'password' => Hash::make('password'),
                    'data' => ['is_seeded' => true],
                    'gender' => $faker->randomElement(['male', 'female']),
                    'birth' => Carbon::now()->subYears(rand(20, 40))->subDays(rand(0, 365)),
                    'email_verified_at' => $now,
                    'status' => User::STATUS_ACTIVE,
                    'is_blocked' => false,
                ]);

                // Checkin/checkout đều trước 2025-12-08 (cutoff dashboard tính revenue).
                $rangeStart = Carbon::create(2024, 6, 1);
                $rangeEnd = Carbon::create(2025, 12, 5);
                $checkIn = Carbon::createFromTimestamp(rand($rangeStart->timestamp, $rangeEnd->timestamp))
                    ->setTime(rand(12, 20), 0, 0);
                $checkOut = (clone $checkIn)->addHours(rand(2, 48));
                $expiresAt = (clone $checkIn)->addDay();

                $dayKey = $checkIn->format('Ymd');
                if (!isset($seqByDay[$dayKey])) {
                    $seqByDay[$dayKey] = $this->getMaxSeqForDay($dayKey);
                }
                $seqByDay[$dayKey]++;
                $sequence = str_pad((string) $seqByDay[$dayKey], 4, '0', STR_PAD_LEFT);
                $code = "BKS{$dayKey}{$sequence}";

                $order = Order::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'check_in' => $checkIn->toDateTimeString(),
                    'check_out' => $checkOut->toDateTimeString(),
                    'number_of_guests' => rand(1, 4),
                    'guest_name' => $name,
                    'guest_phone' => $phone,
                    'total' => rand(500000, 5000000),
                    'note' => $faker->sentence(),
                    'code' => $code,
                    'status' => Order::STATUS_PAID,
                    'expires_at' => $expiresAt->toDateTimeString(),
                ]);

                $rating = (int) ($item['stars'] ?? 5);
                Review::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'rating' => $rating,
                    'content' => $item['content'] ?? '',
                    'cleanliness_rating' => max($rating - 1, 5),
                    'accuracy_rating' => max($rating - 1, 5),
                    'checkin_rating' => max($rating - 1, 5),
                    'communication_rating' => max($rating - 1, 5),
                    'location_rating' => max($rating - 1, 5),
                    'value_rating' => max($rating - 1, 5),
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Đã seed {$totalReviews} reviews ({$rooms->count()} rooms x " . self::REVIEWS_PER_ROOM . ") cho house {$houseId}");

        return self::SUCCESS;
    }

    // Faker vi_VN có thể prefix tên bằng xưng hô dạng "Cô.", "Bà.", "Em." (có dấu chấm) hoặc "Anh", "Chị" (không). Loại bỏ.
    private function generateNameWithoutHonorific($faker): string
    {
        $honorifics = ['Cô', 'Chú', 'Bà', 'Ông', 'Anh', 'Chị', 'Em', 'Bác', 'Cụ', 'Dì', 'Cậu', 'Mợ', 'Thầy', 'Mr', 'Mrs', 'Ms', 'Dr'];

        do {
            $name = trim($faker->name());
            // Loại các prefix dạng "Xxx. " hoặc "Xxx " (có thể lặp lại nếu Faker chèn nhiều cấp)
            $changed = true;
            while ($changed) {
                $changed = false;
                foreach ($honorifics as $h) {
                    foreach ([$h . '. ', $h . ' '] as $prefix) {
                        if (str_starts_with($name, $prefix)) {
                            $name = trim(substr($name, strlen($prefix)));
                            $changed = true;
                            break 2;
                        }
                    }
                }
            }
        } while (empty($name) || str_word_count($name) < 2);

        return $name;
    }

    // Đọc seq lớn nhất hiện có trong DB cho prefix BKS{dayKey} để tiếp tục đánh số, tránh trùng code.
    private function getMaxSeqForDay(string $dayKey): int
    {
        $prefix = "BKS{$dayKey}";
        $maxCode = Order::where('code', 'like', $prefix . '%')->max('code');
        if (!$maxCode) {
            return 0;
        }
        return (int) substr($maxCode, strlen($prefix));
    }

    // Chọn ngẫu nhiên $count reviews từ pool, không trùng. Nếu pool ít hơn $count thì cho phép lặp.
    private function pickReviews(array $pool, int $count): array
    {
        if (count($pool) >= $count) {
            $keys = array_rand($pool, $count);
            $keys = is_array($keys) ? $keys : [$keys];
            return array_map(fn($k) => $pool[$k], $keys);
        }

        $picked = [];
        for ($i = 0; $i < $count; $i++) {
            $picked[] = $pool[array_rand($pool)];
        }
        return $picked;
    }

    // Sinh số điện thoại 10 chữ số bắt đầu bằng "0900" (prefix không thuộc nhà mạng VN nào)
    // và đảm bảo chưa có trong bảng users.
    private function generateUniquePhone(): string
    {
        do {
            $phone = '0900' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('phone', $phone)->orWhere('phone_number', $phone)->exists());

        return $phone;
    }

    private function generateUniqueEmail($faker): string
    {
        do {
            $email = 'seed_' . uniqid() . '_' . $faker->userName() . '@example.com';
        } while (User::where('email', strtolower($email))->exists());

        return $email;
    }
}
