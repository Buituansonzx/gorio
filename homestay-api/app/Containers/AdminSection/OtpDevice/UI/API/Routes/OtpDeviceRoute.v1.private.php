<?php

/**
 * @apiGroup           OtpDevice
 * @apiName
 *
 * @api                {GET} /v1/admin/otp-devices Listing'
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

use App\Containers\AdminSection\OtpDevice\UI\API\Controllers\OtpDeviceController;
use Illuminate\Support\Facades\Route;

Route::get('admin/otp-devices', [OtpDeviceController::class, 'listing'])
    ->middleware(['auth:api']);

Route::post('admin/otp-devices/{id}/toggle-active', [ OtpDeviceController::class, 'toggleActive'])
    ->middleware(['auth:api']);

Route::post('admin/otp-devices', [OtpDeviceController::class, 'create'])
    ->middleware(['auth:api']);

Route::patch('admin/otp-devices/{id}', [OtpDeviceController::class, 'update'])
    ->middleware(['auth:api']);

 