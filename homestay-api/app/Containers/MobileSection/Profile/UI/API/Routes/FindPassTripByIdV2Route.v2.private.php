<?php

/**
 * @apiGroup           Profile
 * @apiName            
 *
 * @api                {GET} /v2/mobile/user/pass-trip/:id Find Pass Trip By Id V2
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

use App\Containers\MobileSection\Profile\UI\API\Controllers\FindPassTripByIdVersionController;
use Illuminate\Support\Facades\Route;

Route::get('mobile/user/pass-trip/{id}', [FindPassTripByIdVersionController::class, 'findPassTripByIdV2'])
    ->middleware(['auth:api']);

