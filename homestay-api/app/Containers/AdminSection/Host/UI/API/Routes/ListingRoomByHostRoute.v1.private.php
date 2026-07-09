<?php

/**
 * @apiGroup           Host
 * @apiName
 *
 * @api                {GET} /v1/host/:id/rooms
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

use App\Containers\AdminSection\Host\UI\API\Controllers\HostController;
use Illuminate\Support\Facades\Route;

Route::get('admin/host/{id}/rooms', [HostController::class, 'getListingByHost']);

