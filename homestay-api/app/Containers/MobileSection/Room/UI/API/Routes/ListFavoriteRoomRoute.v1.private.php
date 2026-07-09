<?php

/**
 * @apiGroup           Room
 * @apiName
 *
 * @api                {GET} /v1/mobile/rooms/favorite
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

use App\Containers\MobileSection\Room\UI\API\Controllers\ListFavoriteRoomController;
use Illuminate\Support\Facades\Route;

Route::get('mobile/rooms/favorite', [ListFavoriteRoomController::class, 'listFavoriteRoom'])
    ->middleware(['auth:api']);

