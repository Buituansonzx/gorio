<?php

namespace App\Containers\AppSection\Notification\Services;

use App\Containers\AppSection\Notification\Events\NotificationCreated;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Helpers\S3Helper;
use App\Ship\Services\ImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function __construct(private readonly OneSignalService $oneSignalService)
    {

    }

    public function createNotificationBooking(Order $order, string $language): void
    {
        $words = explode(' ', $order->room->title);
        $shortTitle = count($words) > 3
            ? implode(' ', array_slice($words, 0, 3)) . '...'
            : $order->room->title;

        $districtName = $order->room->district->getTranslation('name', $language);
        $mediaQuery = $order->room->medias();

        $coverImage = $mediaQuery
            ->where('is_cover', true)
            ->first()
            ?? $mediaQuery->first();

        $imageUrl = null;

        if ($coverImage) {
            $imageUrl = app(ImageService::class)
                ->toMobilePayload(
                    $coverImage,
                    '(max-width: 768px) 100vw, 960px',
                    'content-1440'
                )['img']['src'];
        } else {
            $imageUrl = asset('logo.jpg');
        }
        // Tạo record notification
        $notification = Notification::create([
            'type' => Notification::TYPE_BOOKING,
            'title' => 'Đặt phòng thành công! 🎉',
            'message' => "Phòng {$shortTitle} tại {$districtName} đã được đặt thành công.\nMã: {$order->code} – nhấn để xem chi tiết chuyến đi.",
            'data' => [
                'order_id' => $order->id,
                'check_in' => $order->check_in,
                'check_out' => $order->check_out,
                'target_screen' => Notification::TRIP_SCREEN,
                'image_url' => $imageUrl,
                'body_segments' => [
                    [ "text" => "Phòng " ],
                    [ "text" => $shortTitle, "variant" => "highlight" ],
                    [ "text" => " tại {$districtName} đã được đặt phòng thành công. Xem hướng dẫn check-in tại đây." ],
                ]
            ],
        ]);

        $notification->update([
            'data->notification_id' => $notification->id
        ]);

        $notification->users()->attach($order->user_id);

        $user = $order->user;

        $data = $user->data ?? [];

        if (is_string($data)) {
            $data = json_decode($data, true) ?: [];
        }

        $current = $data['unread_count'] ?? 0;
        $data['unread_count'] = $current + 1;

        $user->data = $data;
        $user->saveQuietly();
        event(new NotificationCreated($notification));
    }

    public function createCheckinReminder(Order $order): void
    {
        // Rút gọn tên phòng
        $words = explode(' ', $order->room->title);
        $shortTitle = count($words) > 3
            ? implode(' ', array_slice($words, 0, 3)) . '...'
            : $order->room->title;

        // Format giờ & ngày
        $checkinTime = Carbon::parse($order->check_in)->format('H:i');
        $checkinDate = Carbon::parse($order->check_in)->format('d/m/Y');

        // Tạo record notification
        $notification = Notification::create([
            'type' => Notification::TYPE_REMINDER,
            'title' => 'SẮP ĐẾN GIỜ CHECK-IN',
            'message' =>
                "Bạn chuẩn bị check-in tại $shortTitle.\n" .
                "Thời gian: $checkinTime – $checkinDate\n" .
                "Vui lòng xem kỹ hướng dẫn check-in và chuẩn bị giấy tờ cần thiết.\n" .
                "Chúc bạn có một chuyến lưu trú tuyệt vời tại Gorio!",
            'data' => [
                'order_id' => $order->id,
                'target_screen' => Notification::TRIP_SCREEN,
                'unread_count' => $order->user->data['unread_count'] ?? 0,
            ],
        ]);

        // Gắn user vào notification
        $notification->users()->attach($order->user_id);

        // Gửi push
        event(new NotificationCreated($notification));
    }

    public function createCheckoutReminder(Order $order)
    {
        $words = explode(' ', $order->room->title);

        $shortTitle = count($words) > 3
            ? implode(' ', array_slice($words, 0, 3)) . '...'
            : $order->room->title;
        $checkoutTime = Carbon::parse($order->check_out)->format('H:i');
        $checkoutDate = Carbon::parse($order->check_out)->format('d/m/Y');
        $notification = Notification::create([
            'type' => Notification::TYPE_REMINDER,
            'title' => 'SẮP ĐẾN GIỜ CHECK-OUT',
            'message' => "Cảm ơn bạn đã lưu trú tại $shortTitle.\nGiờ check-out: $checkoutTime – $checkoutDate\nBạn vui lòng kiểm tra hành lý và đồ dùng cá nhân trước khi rời phòng.\nGorio hi vọng được đồng hành cùng bạn trong những chuyến đi tiếp theo!",
            'data' => [
                'order_id' => $order->id,
                'target_screen' => Notification::TRIP_SCREEN,
                'unread_count' => $order->user->data['unread_count'] ?? 0,
            ],
        ]);
        $notification->users()->attach($order->user_id);
        event(new NotificationCreated($notification));
    }

    public function createNotificationVoucher($voucher)
    {
        $title = $voucher->name;
        $message = $voucher->description;

        if($voucher->is_scheduled == 1) {
            if($voucher->code == Voucher::CODE_RETURN) {
                $title = "🎁 Bạn vừa nhận voucher quay lại 50K từ Gorio";
                $message = "Deal cho chuyến đi tiếp theo đang chờ bạn 👀";
            }
            if($voucher->code == Voucher::CODE_FRIDAYS) {
                $title = "🔥 Deal cuối tuần đã mở!";
                $message = "Giảm ngay 60K cho booking hôm nay.";
            }
            if($voucher->code == Voucher::CODE_SATURDAYS) {
                $title = "🚨 Super Deal cuối tuần!";
                $message = "Giảm 70K — chỉ còn vài giờ.";
            }
        }
        
        $notification = Notification::create([
            'type' => Notification::TYPE_OFFER,
            'title' => $title,
            'message' => $message,
            'data' => [
                'voucher_id' => $voucher->id,
                'image_url' => S3Helper::getS3ImageUrl($voucher->image_path),
                'target_screen' => Notification::HOME_SCREEN,
            ],
        ]);
        $allUserIds = User::pluck('id')->toArray();
        $notification->users()->attach($allUserIds);

        event(new NotificationCreated($notification));
    }

    public function softDeleteAllNotificationsForOrder($userId)
    {
        $notifications = Notification::where('type', Notification::TYPE_BOOKING)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }

    public function softDeleteNotificationForOrder($orderId, $userId)
    {
        $notifications = Notification::where('type', Notification::TYPE_BOOKING)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('data->order_id', $orderId)
            ->get();
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }

    public function scheduleVoucherAvailable($voucher, $userIds, $isBroadcast = false)
    {
        $notification = Notification::create([
            'type' => Notification::TYPE_OFFER,
            'title' => $voucher->name,
            'message' => $voucher->description,
            'data' => [
                'voucher_id' => $voucher->id,
                'image_url' => S3Helper::getS3ImageUrl($voucher->image_path),
                'target_screen' => Notification::HOME_SCREEN,
                'broadcast' => $isBroadcast,
            ],
        ]);
        $notification->users()->attach($userIds);

        event(new NotificationCreated($notification));
    }
}
