<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository as SharedRoomRepository;
use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\Attribute;
use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Containers\SharedSection\Room\Models\Room;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Ship\Traits\RoomPriceCalcTrait;

/**
 * Class RoomRepository
 *
 * Client-specific Room repository that extends the shared Room repository.
 *
 * @template TModel of Room
 * @extends SharedRoomRepository<TModel>
 * @package App\Containers\ClientSection\Room\Data\Repositories
 */
class RoomRepository extends SharedRoomRepository
{
    use RoomPriceCalcTrait;
    /**
     * @var array<string> Additional searchable fields for client queries
     */
    protected $fieldSearchable = [
        'max_guests' => '>=',
        'name' => 'like',
        'district_id' => 'like',
    ];

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Room::class;
    }

    /**
     * Get available rooms for client booking
     */
    public function getAvailableRooms(array $filters = [])
    {
        $query = $this->addRequestCriteria($filters);

        //Kiểm tra phòng được active
        $query = $query->where('is_active', true);
        $userId = Auth::id();

        //Kiểm tra phòng có được yêu thích bởi user hay không
        if (!empty($userId)) {
            $query->withCount([
                'favoritedBy as is_favorite' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->where('favorites.is_favorite', true);
                }
            ]);
        }

        //Lấy phòng theo khoảng thời gian truyền lên
        if (!empty($filters['check_in_time']) && !empty($filters['check_out_time'])) {

            $checkInDate = Carbon::parse($filters['check_in_time']);
            $checkOutDate = Carbon::parse($filters['check_out_time']);
            if(!empty($filters['is_by_hours'])){
                $start = $checkInDate->subMinutes(30);
                $end = ($checkInDate->diffInHours($checkOutDate) <= 6 ? $checkOutDate : $checkInDate->copy()->addHours(6))->addMinutes(30);
                $query = $query->whereDoesntHave('roomLock', function ($q) use ($start, $end) {
                    $q->where('start_time', '<=', $end)
                        ->where('end_time', '>=', $start);
                });
                $query = $query->whereDoesntHave('orders', function ($q) use ($start, $end) {
                    $q->activeForBooking()->where(function ($s) use ($start, $end) {
                        $s->where('check_in', '<=', $end)
                            ->where('check_out', '>=', $start);
                    });
                });
            }else{
                $query = $query->whereDoesntHave('roomLock', function ($q) use ($checkInDate, $checkOutDate) {
                    $q->where('start_time', '<=', $checkOutDate)
                        ->where('end_time', '>=', $checkInDate);
                });
                $query = $query->whereDoesntHave('orders', function ($q) use ($checkInDate, $checkOutDate) {
                    $q->activeForBooking()->where(function ($s) use ($checkInDate, $checkOutDate) {
                        $s->where('check_in', '<=', $checkOutDate)
                            ->where('check_out', '>=', $checkInDate);
                    });
                });
            }
        }
        if (!empty($filters['max_guests'])) {
            $query = $query->where('max_guests', '>=', $filters['max_guests']);
        }

        if (!empty($filters['room_type_id'])) {
            $query = $query->where('room_type_id', $filters['room_type_id']);
        }
        if (!empty($filters['district_id'])) {
            $query = $query->where('district_id', $filters['district_id']);
        }
        if (!empty($filters['amenities'])) {
            $amenityIds = $filters['amenities'];
            $query = $query->whereHas('amenities', function ($q) use ($amenityIds) {
                $q->active()->whereIn('id', $amenityIds);
            }, '=', count($amenityIds));
        }

        if (!empty($filters['province_id'])) {
            $query = $query->whereHas('district', function ($q) use ($filters) {
                $q->where('province_id', $filters['province_id']);
            });
        }
//        if (!empty($filters['is_by_hours'])) {
//            $query = $query->whereHas('hourlyPricing', function ($q) use ($filters) {
//                // Lấy thời gian check-in/check-out
//                $checkIn = !empty($filters['check_in_time'])
//                    ? Carbon::parse($filters['check_in_time'])
//                    : Carbon::now();
//
//                $checkOut = !empty($filters['check_out_time'])
//                    ? Carbon::parse($filters['check_out_time'])
//                    : Carbon::now()->endOfDay();
//
//                //Số giờ thuê
//                $diffHours = $checkIn->diffInHours($checkOut);
//
//                //kiểm tra thời gian thuê có hợp lệ với min_hours của phòng
//                $q->where('min_hours', '<=', $diffHours);
//            });
//        }
        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $minPrice = $filters['min_price'] ?? null;
            $maxPrice = $filters['max_price'] ?? null;
            $voucherDiscount = $this->getMaxGlobalVoucherDiscount(Auth::user());
            $hasVoucher = $voucherDiscount > 0;

            $bufferMap = [
                0 => 'sun_buffer_price',
                1 => 'mon_buffer_price',
                2 => 'tue_buffer_price',
                3 => 'wed_buffer_price',
                4 => 'thu_buffer_price',
                5 => 'fri_buffer_price',
                6 => 'sat_buffer_price',
            ];
            $dayOfWeek = !empty($filters['check_in_time'])
                ? Carbon::parse($filters['check_in_time'])->dayOfWeek
                : Carbon::now()->dayOfWeek;
            $bufferCol = $bufferMap[$dayOfWeek];

            $priceExpr = $hasVoucher
                ? "room_fixed_check_time.price - ? + COALESCE(room_fixed_check_time.$bufferCol, 0)"
                : 'room_fixed_check_time.price';
            $bindings = $hasVoucher ? [$voucherDiscount] : [];

            $query = $query->whereHas('fixedCheckTimeStd', function ($q) use ($minPrice, $maxPrice, $priceExpr, $bindings) {
                if ($minPrice) {
                    $q->whereRaw("$priceExpr >= ?", [...$bindings, $minPrice]);
                }
                if ($maxPrice) {
                    $q->whereRaw("$priceExpr <= ?", [...$bindings, $maxPrice]);
                }
            });
        }

        if (isset($filters['projector'])) {
            $query = $query->whereHas('amenities', function ($q) {
                $q->where('code', Amenity::CODE_PROJECTOR);
            });
        }
        if (isset($filters['bathtub'])) {
            $query = $query->whereHas('amenities', function ($q) {
                $q->where('code', Amenity::CODE_BATHTUB);
            });
        }
        if(isset($filters['balcony'])){
            $query = $query->whereHas('amenities', function ($q) {
                $q->where('code', Amenity::CODE_BALCONY);
            });
        }
        if(isset($filters['duplex'])) {
            $query = $query->whereHas('amenities', function ($q) {
                $q->where('code', Amenity::CODE_DUPLEX);
            });
        }
        if (isset($filters['self_checkin'])) {
            $query = $query->whereHas('checkinMethods', function ($q) {
                $q->where('code', CheckinMethod::CODE_SELF_CHECKIN);
            });
        }
        if (isset($filters['parking_for_car'])) {
            $query = $query->whereHas('parkingRules', function ($q) {
                $q->where('vehicle_type', 'car')
                    ->where('has_parking', true);
            });
        }
        if (!empty($filters['bedroom_count'])) {
            $query = $query->whereHas('attributes', function ($q) use ($filters) {
                $q->where('code', Attribute::CODE_BEDROOM)
                    ->where('quantity', '>=', $filters['bedroom_count']);
            });
        }
        if (!empty($filters['bed_count'])) {
            $query = $query->whereHas('attributes', function ($q) use ($filters) {
                $q->where('code', Attribute::CODE_BED)
                    ->where('quantity', '>=', $filters['bed_count']);
            });
        }
        if (!empty($filters['bathroom_count'])) {
            $query = $query->whereHas('attributes', function ($q) use ($filters) {
                $q->where('code', Attribute::CODE_BATHROOM)
                    ->where('quantity', '>=', $filters['bathroom_count']);
            });
        }
        if (isset($filters['is_loved_by_everyone']) && $filters['is_loved_by_everyone']) {
            $query = $query->whereHas('reviews', function ($q) {
                $q->select('room_id', DB::raw('AVG(rating) as avg_rating'))
                    ->groupBy('room_id')
                    ->havingRaw(
                        'ROUND(AVG(rating), 1) >= ?',
                        [Room::SCORE_LOVED_BY_EVERYONE]
                    );
            });
        }

        $query = $query
            ->leftJoin('score_rank', 'score_rank.room_id', '=', 'rooms.id')
            ->select('rooms.*')
            ->addSelect('score_rank.score')
            ->orderByRaw('COALESCE(score_rank.score, 0) DESC');

        $now = (int)Carbon::now('Asia/Bangkok')->hour;
        $checkinDateTime = $filters['check_in_time'] ?? null;

        $eagerLoads = [
            'fixedCheckTime', 'reviews', 'hourlyPricing',
            'specialOffers', 'pricingPolicy', 'fixedCheckTimeStd',
            'comboPricing' => function ($q) use ($checkinDateTime, $now) {
                if (!$checkinDateTime) {
                    // Không chọn ngày thì lọc theo thời điểm hiện tại
                    $q->where('start_time', '>', $now);
                } else {
                    $checkin = Carbon::parse($checkinDateTime);
                    if ($checkin->isToday()) {
                        // Chọn ngày hôm nay thì chỉ lấy combo chưa bắt đầu
                        $q->where('start_time', '>', $now);
                    } elseif ($checkin->isPast()) {
                        // Chọn ngày đã qua thì không có combo nào hợp lệ
                        $q->whereRaw('1 = 0');
                    }
                }

                $q->orderBy('start_time', 'asc');
            },
            'attributes', 'medias' => function ($q) {
                $q->orderByDesc('is_cover')
                    ->orderBy('sort_index', 'asc')
                    ->limit(5);
            }
        ];

        $query->with($eagerLoads);

        $minPrice = $filters['min_price'] ?? null;
        $maxPrice = $filters['max_price'] ?? null;

        // if ($minPrice || $maxPrice) {
        //     // Get all records to filter in memory
        //     $rooms = $query->get();

        //     $checkIn = !empty($filters['check_in_time']) ? Carbon::parse($filters['check_in_time']) : Carbon::now();
        //     $checkOut = !empty($filters['check_out_time']) ? Carbon::parse($filters['check_out_time']) : Carbon::tomorrow();
        //     $adults = $filters['num_of_adults'] ?? 1;

        //     $maxDiscount = $this->getMaxGlobalVoucherDiscount(Auth::user());

        //     $filteredRooms = $rooms->filter(function ($room) use ($minPrice, $maxPrice, $checkIn, $checkOut, $adults, $maxDiscount) {
        //         // Determine price based on price_after_voucher instead of final_price
        //         $priceData = $this->calcPrice($checkIn, $checkOut, (int)$adults, $room, null, false);
        //         $finalPrice = is_array($priceData) ? ($priceData['final_price'] - $maxDiscount) : 0;

        //         if ($minPrice && $finalPrice < $minPrice) return false;
        //         if ($maxPrice && $finalPrice > $maxPrice) return false;

        //         return true;
        //     });

        //     // Manual pagination
        //     $page = LengthAwarePaginator::resolveCurrentPage();
        //     $perPage = 20;
        //     $results = $filteredRooms->slice(($page - 1) * $perPage, $perPage)->values();

        //     return new LengthAwarePaginator(
        //         $results,
        //         $filteredRooms->count(),
        //         $perPage,
        //         $page,
        //         [
        //             'path' => LengthAwarePaginator::resolveCurrentPath(),
        //             'query' => request()->query(),
        //         ]
        //     );
        // }

        return $query->paginate(20);
    }

    /**
     * Get room details for client view
     */
    public function getClientRoomDetails($request, string $id)
    {
        $now = Carbon::now('Asia/Bangkok')->hour;
        $checkInTime = $request['check_in_time'] ?? null;
        return $this->with(['attributes',
            'surroundingFacilities',
            'medias',
            'medias.imageAreaGroup',
            'amenities',
            'amenities.group',
            'orders.review.user',
            'orders.review',
            'highlightAmenities.amenity',
            'highlightAmenities.images',
            'host.rooms.orders.review',

            'comboPricing' => function ($query) use ($checkInTime, $now) {
                if ($checkInTime) {
                    $checkIn = Carbon::parse($checkInTime);

                    // Nếu là ngày hiện tại, chỉ lấy start_time > giờ hiện tại
                    if ($checkIn->isToday()) {
                        $query->where('start_time', '>', $now)
                            ->orderBy('start_time', 'asc');
                    } else {
                        // Ngày tương lai thì lấy tất cả
                        $query->orderBy('start_time', 'asc');
                    }
                } else {
                    // Nếu không có check-in time, cũng trả tất cả
                    $query->orderBy('start_time', 'asc');
                }
            },])
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();
    }

    /**
     * Search rooms with client-specific filters
     */
    public function searchForClient(array $criteria)
    {
        $query = $this->model->availableForClient();

        // Apply search criteria
        foreach ($criteria as $field => $value) {
            if (in_array($field, array_merge($this->fieldSearchable, $this->clientSearchableFields))) {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, 'like', "%{$value}%");
                }
            }
        }

        return $query->with(['roomType', 'images' => function ($q) {
            $q->primary();
        }])->paginate();
    }
}
