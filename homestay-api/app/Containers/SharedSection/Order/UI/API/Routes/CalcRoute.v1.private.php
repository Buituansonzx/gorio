<?php

/**
 * @apiGroup           Order
 * @apiName
 *
 * @api                {GET} /v1/calc Calc
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

use App\Containers\SharedSection\Order\UI\API\Controllers\CalcController;
use Illuminate\Support\Facades\Route;

Route::get('calc', [CalcController::class, 'calc']);

