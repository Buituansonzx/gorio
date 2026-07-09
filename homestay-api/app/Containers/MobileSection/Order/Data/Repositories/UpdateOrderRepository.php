<?php

namespace App\Containers\MobileSection\Order\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Order
 *
 * @extends ParentRepository<TModel>
 */
final class UpdateOrderRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
    public function model(): string
    {
        return Order::class;
    }

    public function updateOrder(array $orderData, $orderId): Order
    {
        return $this->update($orderData, $orderId);
    }
}
