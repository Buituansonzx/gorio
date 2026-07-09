<?php

namespace App\Containers\AdminSection\Order\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Order\Actions\UpdateOrderAction;
use App\Containers\AdminSection\Order\Actions\CancelOrderAction;
use App\Containers\AdminSection\Order\Actions\CreateOrderAction;
use App\Containers\AdminSection\Order\Actions\DetailOrderAction;
use App\Containers\AdminSection\Order\Actions\ListingOrderAction;
use App\Containers\AdminSection\Order\UI\API\Requests\CancelOrderRequest;
use App\Containers\AdminSection\Order\UI\API\Requests\CreateOrderRequest;
use App\Containers\AdminSection\Order\UI\API\Requests\ListingOrderRequest;
use App\Containers\AdminSection\Order\UI\API\Requests\UpdateOrderRequest;
use App\Containers\AdminSection\Order\UI\API\Transformers\OrderDetailTransformer;
use App\Containers\AdminSection\Order\UI\API\Transformers\OrderTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;


final class OrderController extends ApiController
{
    public function listingOrders(ListingOrderRequest $request, ListingOrderAction $action)
    {
        $orders = $action->run($request);
        return Response::create( $orders,OrderTransformer::class);
    }
    public function cancelOrder(CancelOrderRequest $request, CancelOrderAction $action)
    {
        $orderId = $request->id;
        $order = $action->run($orderId);
        return response()->json([
            'status' => 'success',
            'message' => 'Order cancelled successfully',
            'order_id' => $order->id,
            'order_status' => $order->status,
        ], 200);
    }

    public function detailOrder(Request $request, DetailOrderAction $action)
    {
        $orderId = $request->id;
        $order = $action->run($orderId);
        return Response::create( $order,OrderDetailTransformer::class);
    }

    public function createOrder(CreateOrderRequest $request, CreateOrderAction $action)
    {
        $oder = $action->run($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'order_id' => $oder->id,
        ], 201);
    }

    public function updateOrder(UpdateOrderRequest $request, UpdateOrderAction $action)
    {
        $order = $action->run($request->id, $request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'order_id' => $order->id,
        ], 200);
    }
}
