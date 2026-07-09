<?php

/**
 * @apiGroup           Room
 * @apiName
 *
 * @api                {GET} /v1/room-image-area-group Invoke
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

use App\Containers\ClientSection\Room\UI\API\Controllers\GetRoomImageAreaGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogRequestMiddleware;

Route::get('room-image-area-group', GetRoomImageAreaGroupController::class)->middleware(LogRequestMiddleware::class);

