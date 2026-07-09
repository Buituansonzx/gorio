<?php

/**
 * @apiGroup           Dashboard
 * @apiName
 *
 * @api                {GET} /v1/admin/top-room-by-order
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

use App\Containers\AdminSection\Dashboard\UI\API\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('admin/top-room-by-order', [DashboardController::class, 'topRoomByOrder']);

