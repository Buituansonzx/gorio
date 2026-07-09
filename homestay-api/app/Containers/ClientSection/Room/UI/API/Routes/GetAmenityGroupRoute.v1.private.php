<?php

/**
 * @apiGroup           Room
 * @apiName
 *
 * @api                {GET} /v1/amenity-group Invoke
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

use App\Containers\ClientSection\Room\UI\API\Controllers\GetAmenityGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogRequestMiddleware;

Route::get('amenity-group', GetAmenityGroupController::class)->middleware(LogRequestMiddleware::class);
