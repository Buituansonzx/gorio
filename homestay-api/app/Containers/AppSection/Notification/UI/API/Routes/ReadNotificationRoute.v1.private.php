<?php

/**
 * @apiGroup           Notification
 * @apiName
 *
 * @api                {POST} /v1/notifications/:id/read Invoke
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

use App\Containers\AppSection\Notification\UI\API\Controllers\ReadNotificationController;
use Illuminate\Support\Facades\Route;

Route::post('notifications/{id}/read', [ReadNotificationController::class,'read'])
    ->middleware(['auth:api']);
Route::post('notifications/read-all', [ReadNotificationController::class,'readAll'])
    ->middleware(['auth:api']);

