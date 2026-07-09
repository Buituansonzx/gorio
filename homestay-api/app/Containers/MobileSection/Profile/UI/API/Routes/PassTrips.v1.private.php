<?php

/**
 * @apiGroup           Profile
 * @apiName            
 *
 * @api                {GET} /v1/mobile/user/pass-trips Invoke
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

use App\Containers\MobileSection\Profile\UI\API\Controllers\PassTripsController;
use Illuminate\Support\Facades\Route;

Route::get('mobile/user/pass-trips', PassTripsController::class)
    ->middleware(['auth:api']);

