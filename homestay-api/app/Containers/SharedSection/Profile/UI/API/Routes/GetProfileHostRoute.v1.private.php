<?php

/**
 * @apiGroup           Profile
 * @apiName
 *
 * @api                {GET} /v1/host/:id/profile Invoke
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

use App\Containers\SharedSection\Profile\UI\API\Controllers\GetProfileHostController;
use Illuminate\Support\Facades\Route;

Route::get('host/{id}/profile', GetProfileHostController::class);

