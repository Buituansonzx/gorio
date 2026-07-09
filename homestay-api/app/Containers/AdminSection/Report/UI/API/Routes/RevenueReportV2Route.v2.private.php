<?php

/**
 * @apiGroup           Report
 * @apiName            
 *
 * @api                {GET} /v2/admin/report/revenue Report V2
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

use App\Containers\AdminSection\Report\UI\API\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('admin/report/revenue', [ReportController::class, 'reportV2']);

