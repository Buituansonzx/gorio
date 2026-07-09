<?php

namespace App\Containers\AdminSection\Voucher\UI\API\Transformers;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class VoucherTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Voucher $voucher): array
    {
        $createByUser = User::find($voucher->created_by);
        if($voucher->end_date && $voucher->end_date < now()){
            $status = 2;
        }elseif ($voucher->quantity == $voucher->used_count){
            $status = 3;
        }else{
            $status = $voucher->is_active;
        }
        return [
            'type' => $voucher->getResourceKey(),
            'id' => $voucher->id,
            'code' => $voucher->code,
            'name' => $voucher->name,
            'image' => S3Helper::getS3ImageUrl($voucher->image_path),
            'description' => $voucher->description,
            'discount_type' => $voucher->discount_type,
            'type_voucher' => $voucher->type,
            'discount_value' => $voucher->discount_value,
            'max_discount_amount' => $voucher->max_discount_amount,
            'min_order_amount' => $voucher->min_order_amount,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'quantity' => $voucher->quantity,
            'usage_limit' => $voucher->usage_limit,
            'used_count' => $voucher->used_count,
            'is_active' => $voucher->is_active,
            'status' => $status,
            'create_by' => $createByUser->name ?? 'System',
            'created_at' => $voucher->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $voucher->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
