<?php

/**
 * @apiGroup           Profile
 * @apiName
 *
 * @api                {GET} /v2/mobile/user/trip/:id Invoke
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         2.0.0
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

use Illuminate\Support\Facades\Route;
use App\Containers\MobileSection\Profile\UI\API\Controllers\FindTripByIdV2Controller;

Route::get('mobile/user/trip/{id}', FindTripByIdV2Controller::class)
    ->middleware(['auth:api']);

