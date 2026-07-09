<?php

/**
 * @apiGroup           Order
 * @apiName
 *
 * @api                {GET} /v1/trips/:id/public Invoke
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

use App\Containers\SharedSection\Order\UI\API\Controllers\FindTripByIdController;
use Illuminate\Support\Facades\Route;

Route::get('trips/{id}/public', FindTripByIdController::class);

