<?php

/**
 * @apiGroup           Room
 * @apiName
 *
 * @api                {POST} /v1/upload/room-image
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

use App\Containers\SharedSection\Room\UI\API\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::post('upload/room-image', [MediaController::class, 'uploadZipImage']);

