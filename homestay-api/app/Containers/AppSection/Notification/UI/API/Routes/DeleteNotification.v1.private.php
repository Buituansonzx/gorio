<?php

/**
 * @apiGroup           Notification
 * @apiName            Delete
 *
 * @api                {DELETE} /v1/notifications/:id Invoke
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

use App\Containers\AppSection\Notification\UI\API\Controllers\DeleteNotificationController;
use Illuminate\Support\Facades\Route;

Route::delete('notifications/{id}', DeleteNotificationController::class)
    ->middleware(['auth:api']);

