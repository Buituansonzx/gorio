<?php

/**
 * @apiGroup           Notification
 * @apiName
 *
 * @api                {POST} /v1/notification/sms/webhook Send
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

use App\Containers\SharedSection\Notification\UI\API\Controllers\SendNotiErrorSmsController;
use Illuminate\Support\Facades\Route;

Route::post('notification/sms/webhook', [SendNotiErrorSmsController::class, 'send']);

