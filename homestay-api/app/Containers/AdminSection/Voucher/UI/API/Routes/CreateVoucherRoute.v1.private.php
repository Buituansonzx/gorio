<?php

/**
 * @apiGroup           Order
 * @apiName
 *
 * @api                {POST} /v1/voucher Create
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

use Illuminate\Support\Facades\Route;
use App\Containers\AdminSection\Voucher\UI\API\Controllers\VoucherController;

Route::post('admin/voucher', [VoucherController::class, 'create'])
    ->middleware(['auth:api']);

