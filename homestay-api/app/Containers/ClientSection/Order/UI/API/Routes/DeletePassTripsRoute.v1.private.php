<?php

/**
 * @apiGroup           Order
 * @apiName
 *
 * @api                {PUT} /v1/pass-trips/delete Delete
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

use App\Containers\ClientSection\Order\UI\API\Controllers\DeletePassTripsController;
use Illuminate\Support\Facades\Route;

Route::post('pass-trips/delete', [DeletePassTripsController::class, 'delete'])->middleware('auth:api');

