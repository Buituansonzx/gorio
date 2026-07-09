<?php

namespace App\Containers\ClientSection\Order\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Repositories\Repository as ParentRepository;
use Illuminate\Support\Facades\DB;

/**
 * @template TModel of Order
 *
 * @extends ParentRepository<TModel>
 */
final class CreateOrderRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return Order::class;
    }
    public function createOrder(array $orderData): Order
    {
            return $this->create($orderData);
    }
}
