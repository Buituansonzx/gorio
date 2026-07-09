<?php

namespace App\Containers\AdminSection\Voucher\Actions;

use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateVoucherAction extends ParentAction
{
    public function run($voucherId, $data)
    {
        $voucher = Voucher::find($voucherId);
        if (!$voucher) {
            throw new ModelNotFoundException("Voucher with ID {$voucherId} not found.");
        }

        if(!empty($data['code'])){
            $voucher->code = $data['code'];
        }
        if(!empty($data['name'])){
            $voucher->name = $data['name'];
        }
        if(!empty($data['description'])){
            $voucher->description = $data['description'];
        }
        if(!empty($data['discount_type'])){
            $voucher->discount_type = $data['discount_type'];
        }
        if(!empty($data['type'])){
            $voucher->type = $data['type'];
        }
        if (array_key_exists('discount_value', $data) && $data['discount_value'] !== '') {
            $voucher->discount_value = $data['discount_value'];
        }
        if(array_key_exists('max_discount_amount', $data) && $data['max_discount_amount'] !== '') {
            $voucher->max_discount_amount = $data['max_discount_amount'];
        }

        if(array_key_exists('min_order_amount', $data) && $data['min_order_amount'] !== '') {
            $voucher->min_order_amount = $data['min_order_amount'];
        }
        if(!empty($data['start_date'])){
            $voucher->start_date = $data['start_date'];
        }
        if(!empty($data['end_date'])){
            $voucher->end_date = $data['end_date'];
        }
        if(array_key_exists('quantity', $data) && $data['quantity'] !== '') {
            $voucher->quantity = $data['quantity'];
        }
        if(array_key_exists('usage_limit', $data) && $data['usage_limit'] !== '') {
            $voucher->usage_limit = $data['usage_limit'];
        }
        if(array_key_exists('is_active', $data)) {
            $voucher->is_active = $data['is_active'];
        }

        if(!empty($data['image'])){
            $file = request()->file('image');
            $dir = "voucher/image/{$voucher->id}";
            $path = $file->store($dir, 's3');
            $voucher->image_path = $path;
        }

        $voucher->save();

        return $voucher;
    }
}
