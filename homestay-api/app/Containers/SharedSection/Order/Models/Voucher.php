<?php

namespace App\Containers\SharedSection\Order\Models;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Voucher extends ParentModel
{
    use HasUuids;

    protected $table = 'vouchers';

    protected $guarded = [];

    CONST TYPE_VOUCHER_PRIVATE = 'private';
    CONST TYPE_VOUCHER_PUBLIC = 'public';
    CONST CODE_RETURN = 'RETURN50K';
    CONST CODE_FRIDAYS = 'FRIDAY60K';
    CONST CODE_SATURDAYS = 'SATURDAY70K';

    CONST CODE_100K_LANDAU = '100KLANDAU';

    CONST ID_100K = '019afc5f-7083-700a-8984-04dab2947088';

    CONST MAX_DISCOUNT_AMOUNT = 100000;


    /**
     * Relationship: Voucher has many Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_id');
    }

    /**
     * Relationship: Voucher created by a User
     */
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateDiscountAmount($priceTotal)
    {
        // Nếu voucher không hợp lệ hoặc không có giá trị
        if (!$this->discount_value || $priceTotal <= 0) {
            return $priceTotal;
        }

        // Nếu voucher không hợp lệ hoặc không có giá trị
        if (!$this->discount_value || $priceTotal <= 0) {
            return 0;
        }

        $discountAmount = 0;

        if ($this->discount_type === 'percentage') {
            $discountAmount = $priceTotal * ($this->discount_value / 100);

            // Nếu có giới hạn giảm tối đa
            if (!empty($this->max_discount_amount)) {
                $discountAmount = min($discountAmount, $this->max_discount_amount);
            }

            // Làm tròn lên 5000 cho phần trăm
            $discountAmount = floor($discountAmount / 5000) * 5000;

        } elseif ($this->discount_type === 'fixed') {
            $discountAmount = $this->discount_value;
        }

        return $discountAmount;
    }
}
