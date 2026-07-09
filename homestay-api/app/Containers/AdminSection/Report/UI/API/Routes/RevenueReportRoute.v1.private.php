<?php

/**
 * @apiGroup           Report
 * @apiName            
 *
 * @api                {GET} /v1/admin/report/revenue Revenue Report
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

use App\Containers\AdminSection\Report\UI\API\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('admin/report/revenue', [ReportController::class, 'revenueReport'])
    ->middleware(['auth:api']);

Route::get('admin/report/revenue-v2', [ReportController::class, 'reportV2']);    



