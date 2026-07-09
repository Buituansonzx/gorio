<?php

/**
 * @apiGroup           Room
 * @apiName            getRoomTimePricing
 *
 * @api                {GET} /v1/admin/rooms/{id}/time-pricing Invoke
 * @apiDescription     Lấy tất cả khung giờ cố định (room_fixed_check_time) và combo giờ/giá (room_combo_pricing) của phòng.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated Admin
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id Room UUID
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": "success",
 *   "data": {
 *     "fixed_check_times": [ ... ],
 *     "combo_pricing": [ ... ]
 *   }
 * }
 */

use App\Containers\AdminSection\Room\UI\API\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('admin/rooms/{id}/time-pricing', [RoomController::class, 'getTimePricing']);
