<?php

/**
 * @apiGroup           Order
 * @apiName
 *
 * @api                {GET} /v1/admin/orders
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} parameters here...
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     // Insert the response of the request here...
 * }
 */

use App\Containers\AdminSection\Order\UI\API\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('admin/orders', [OrderController::class, 'listingOrders']);

Route::put('admin/order/{id}/cancel', [OrderController::class, 'cancelOrder']);

Route::get('admin/order/{id}', [OrderController::class, 'detailOrder']);

Route::post('admin/order', [OrderController::class, 'createOrder']);

Route::patch('admin/order/{id}', [OrderController::class, 'updateOrder']);

