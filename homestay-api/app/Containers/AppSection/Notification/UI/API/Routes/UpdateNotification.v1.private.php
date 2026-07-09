<?php

/**
 * @apiGroup           Notification
 * @apiName            Update
 *
 * @api                {PATCH} /v1/notifications/:id Invoke
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

use App\Containers\AppSection\Notification\UI\API\Controllers\UpdateNotificationController;
use Illuminate\Support\Facades\Route;

Route::patch('notifications/{id}', UpdateNotificationController::class)
    ->middleware(['auth:api']);

