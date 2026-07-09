<?php

/**
 * @apiGroup           Room
 * @apiName            updateRoomTimePricing
 *
 * @api                {PUT} /v1/admin/rooms/{id}/time-pricing Invoke
 * @apiDescription     Cập nhật giá và buffer cho các khung giờ cố định (room_fixed_check_time)
 *                     và combo giờ/giá (room_combo_pricing) của phòng.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated Admin
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String}  id                                       Room UUID
 * @apiParam           {Object[]} [fixed_check_times]                     Danh sách khung giờ cố định cần cập nhật
 * @apiParam           {String}   fixed_check_times.id                    Fixed check time UUID
 * @apiParam           {Integer}  [fixed_check_times.price]
 * @apiParam           {Integer}  [fixed_check_times.mon_price]
 * @apiParam           {Integer}  [fixed_check_times.sun_buffer_price]    ... (mon..sun_price, mon..sun_buffer_price)
 * @apiParam           {Object[]} [combo_pricing]                         Danh sách combo cần cập nhật
 * @apiParam           {String}   combo_pricing.id                        Room combo pricing UUID
 * @apiParam           {String}   [combo_pricing.start_time]              HH:mm
 * @apiParam           {String}   [combo_pricing.end_time]                HH:mm
 * @apiParam           {Integer}  [combo_pricing.price]
 * @apiParam           {Integer}  [combo_pricing.sun_buffer_price]        ... (mon..sun_price, mon..sun_buffer_price)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": "success",
 *   "message": "Room time pricing updated successfully",
 *   "data": {
 *     "fixed_check_times": [ ... ],
 *     "combo_pricing": [ ... ]
 *   }
 * }
 */

use App\Containers\AdminSection\Room\UI\API\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::put('admin/rooms/{id}/time-pricing', [RoomController::class, 'updateTimePricing']);
