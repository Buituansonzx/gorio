<?php

/**
 * @apiGroup           Room
 * @apiName
 *
 * @api                {GET} /v1/admin/rooms Invoke
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

use App\Containers\AdminSection\Room\UI\API\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('admin/rooms', [RoomController::class, 'listingRoom']);

Route::put('admin/room/{id}/update-status', [RoomController::class, 'updateStatusRoom']);

Route::get('admin/rooms/update-order', [RoomController::class, 'updateRoomOrder']);

Route::post('admin/rooms/import-ranking', [RoomController::class, 'importRanking']);

Route::post('admin/rooms/{id}', [RoomController::class, 'update']);

Route::get('admin/rooms/medias/{id}', [RoomController::class, 'getMediasRoom']);

Route::post('admin/rooms/medias/sort/{id}', [RoomController::class, 'sortMedias']);

Route::post('admin/room/medias/{id}', [RoomController::class, 'addMedia']);



