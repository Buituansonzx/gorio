<?php

namespace App\Containers\SharedSection\Order\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


final class ListVoucherTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Voucher $voucher): array
    {
        $priceTotal = request()->input('price_total');
        $discountAmount = $voucher->calculateDiscountAmount($priceTotal);
        $available = false;
        if (is_null($voucher->min_order_amount) || $voucher->min_order_amount <= $priceTotal) {
            $available = true;
        }
        $endDate = $voucher->end_date;
        $subDescription = '';
        //Check điều kiện voucher RETURN
        if($voucher->type == Voucher::TYPE_VOUCHER_PUBLIC && $voucher->is_scheduled == true && $voucher->code == Voucher::CODE_RETURN){
            $checkUseVoucher = Order::where('user_id', Auth::id())->where('voucher_id', $voucher->id)->where('status', Order::STATUS_PAID)->first();
            $latestOrder = Order::where('user_id', Auth::id())->where('status', Order::STATUS_PAID)->orderBy('check_out', 'desc')->first();
            $now = Carbon::now();
            if($latestOrder) {
                $checkout = Carbon::parse($latestOrder->check_out);
                $diffInHours = $checkout->diffInHours($now);
                if ((($diffInHours > 24 && $diffInHours < 336) || empty($checkUseVoucher))) {
                    $available = true;
                }else {
                    $available = false;
                }
                $endDate = Carbon::parse($latestOrder->check_out)->addDays(14)->format('Y-m-d');
                $expiryDate = Carbon::parse($latestOrder->check_out)->addDays(14);
                $now = Carbon::now();

                if ($now->greaterThan($expiryDate)) {
                    $subDescription = "\n⏰ Đã hết hạn";
                } else {
                    $diffInDays = $now->diffInDays($expiryDate);
                    
                    if ($diffInDays < 1) {
                        $diffInHours = $now->diffInHours($expiryDate);
                         $subDescription = "\n⏰ Còn: " . $diffInHours . " giờ";
                    } else {
                        $subDescription = "\n⏰ Còn: " . round($diffInDays) . " ngày";
                    }
                }
            }else{
                $available = false;
                $subDescription = "\n⏰ Chưa đủ điều kiện sử dụng";
            }
        }
        //Check điều kiện voucher FRIDAYS
        if($voucher->type == Voucher::TYPE_VOUCHER_PUBLIC && $voucher->is_scheduled == true && $voucher->code == Voucher::CODE_FRIDAYS){
            $now = Carbon::now();
            $currentDay = $now->dayOfWeek; // 0 = Sunday, 5 = Friday
            if($currentDay == Carbon::FRIDAY){
                $available = true;
                $endOfFriday = $now->copy()->endOfDay();
                $diffInHours = $now->diffInHours($endOfFriday);
                $subDescription = "\n⏰ Hết hạn sau: " . $diffInHours . " giờ";
                $endDate = $now->format('Y-m-d');
            }else{
                $available = false;
                $subDescription = "\n⏰ Đã hết hạn";
                //Ngày thứ 6 của tuần tiếp theo
                $endDate = $now->startOfWeek(Carbon::MONDAY)->next(Carbon::FRIDAY)->format('Y-m-d');
            }
        }
        //Check điều kiện voucher SATURDAYS
        if($voucher->type == Voucher::TYPE_VOUCHER_PUBLIC && $voucher->is_scheduled == true && $voucher->code == Voucher::CODE_SATURDAYS){
            $now = Carbon::now();
            $currentDay = $now->dayOfWeek; // 0 = Sunday, 6 = Saturday

            if($currentDay == Carbon::SATURDAY){
                $available = true;
                $endOfSaturday = $now->copy()->endOfDay();
                $diffInHours = $now->diffInHours($endOfSaturday);
                $subDescription = "\n⏰ Còn: " . $diffInHours . " giờ";
                $endDate = $now->format('Y-m-d');
            }else{
                $available = false;
                $subDescription = "\n⏰ Đã hết hạn";
                $endDate = $now->startOfWeek(Carbon::MONDAY)->next(Carbon::SATURDAY)->format('Y-m-d');
            }
        }
        return [
            'type' => $voucher->getResourceKey(),
            'id' => $voucher->id,
            'code' => $voucher->code,
            'name' => $voucher->name,
            'image' => S3Helper::getS3ImageUrl($voucher->image_path) ?? null,
            'description' => $voucher->description . $subDescription,
            'discount_amount' => $discountAmount,
            'end_date' => $endDate,
            'available' => $available,
        ];
    }
}
