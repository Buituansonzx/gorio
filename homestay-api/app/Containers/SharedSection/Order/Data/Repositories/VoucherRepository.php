<?php

namespace App\Containers\SharedSection\Order\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Voucher
 *
 * @extends ParentRepository<TModel>
 */
final class VoucherRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return Voucher::class;
    }

    public function listVoucher($filters)
    {
        $query = $this->model;

        if (!empty($filters['key'])) {
            $key = $filters['key'];
            $query = $query->where(function ($q) use ($key) {
                $q->where('id', 'like', "%$key%")
                    ->orWhere('code', 'like', "%$key%");
            });
        }
        if (isset($filters['status'])) {
            if($filters['status'] == 1 || $filters['status'] == 0 ){
                $query = $query->where('is_active', $filters['status']);
            }elseif($filters['status'] == 2){
                $query = $query->where('end_date', '<', now());
            }else{
                $query = $query->whereColumn('used_count', '=', 'quantity');
            }
        }
        if(!empty($filters['type'])) {
            $query = $query->where('discount_type', $filters['type']);
        }
        return $query->orderBy('created_at', 'desc')->paginate($filters['page_size'] ?? 10);
    }
}
