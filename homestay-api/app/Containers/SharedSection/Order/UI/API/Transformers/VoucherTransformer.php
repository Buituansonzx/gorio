<?php

namespace App\Containers\SharedSection\Order\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

final class VoucherTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Voucher $voucher): array
    {
        $price_total = request()->input('price_total');
        $discountAmount = $voucher->calculateDiscountAmount($price_total);
        $available = false;
        if (is_null($voucher->min_order_amount) || $voucher->min_order_amount <= $price_total) {
            $available = true;
        }
        //Check điều kiện voucher RETURN
        if($voucher->type == Voucher::TYPE_VOUCHER_PUBLIC && $voucher->code == Voucher::CODE_RETURN){
            $latestOrder = Order::where('user_id', Auth::id())->where('status', Order::STATUS_PAID)->orderBy('check_out', 'desc')->first();
            $now = now();
            if($latestOrder) {
                $checkout = Carbon::parse($latestOrder->check_out);
                $diffInHours = $checkout->diffInHours($now);
                if ($diffInHours > 24) {
                    $available = true;
                }else {
                    $available = false;
                }
            }
        }
        return [
            'type' => $voucher->getResourceKey(),
            'id' => $voucher->id,
            'code' => $voucher->code,
            'name' => $voucher->name,
            'image' => S3Helper::getS3ImageUrl($voucher->image_path) ?? null,
            'description' => $voucher->description,
            'discount_amount' => $discountAmount,
            'end_date' => $voucher->end_date,
            'available' => $available,
            'condition_apply' => $voucher->condition_apply,
        ];
    }
}
