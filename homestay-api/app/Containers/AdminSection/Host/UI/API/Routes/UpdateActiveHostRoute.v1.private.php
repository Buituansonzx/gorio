<?php

/**
 * @apiGroup           Host
 * @apiName
 *
 * @api                {PUT} /v1/admin/host/update-active/:id
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

Route::put('admin/host/active/{id}', [HostController::class, 'updateActive']);

