<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends ParentModel
{
    use HasUuids;

    protected $table = 'rooms';

    // Specify that we're using UUID as primary key
    protected $keyType = 'string';

    const STATUS_OPENED = '1';
    const STATUS_CLOSED = '-1';

    const SCORE_LOVED_BY_EVERYONE = 4.8;
    public $incrementing = false;

    protected $guarded = [];

    /**
     * Relationship: Room belongs to a room type
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    /**
     * Relationship: Room belongs to an access type
     */
    public function accessType(): BelongsTo
    {
        return $this->belongsTo(RoomAccessType::class, 'access_type_id');
    }

    /**
     * Relationship: Room belongs to a house
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    /**
     * Relationship: Room belongs to a host (direct relationship for easier queries)
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    /**
     * Relationship: Room has many attributes
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            Attribute::class, 'room_attributes', 'room_id', 'attribute_id')->withPivot('quantity');
    }

    /**
     * Relationship: Room has many surrounding facilities
     */
    public function surroundingFacilities(): BelongsToMany
    {
        return $this->belongsToMany(SurroundingFacility::class, 'room_surrounding_facilities', 'room_id', 'facility_id');
    }

    /**
     * Relationship: Room has many images
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }

    /**
     * Relationship: Room has many highlight facilities
     */
    public function highlightFacilities(): HasMany
    {
        return $this->hasMany(RoomHighlightFacility::class, 'room_id');
    }

    /**
     * Relationship: Room has many pricing policies
     */
    public function pricingPolicy(): HasOne
    {
        return $this->hasOne(RoomPricingPolicy::class, 'room_id');
    }

    /**
     * Relationship: Room has many price histories
     */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(RoomPriceHistory::class, 'room_id');
    }

    /**
     * Relationship: Room has many discount policies
     */
    public function discountPolicies(): HasMany
    {
        return $this->hasMany(RoomDiscountPolicy::class, 'room_id');
    }

    /**
     * Relationship: Room has many hourly pricing
     */
    public function hourlyPricing(): HasMany
    {
        return $this->hasMany(RoomHourlyPricing::class, 'room_id');
    }

    /**
     * Relationship: Room has many combo pricing
     */
    public function comboPricing(): HasMany
    {
        return $this->hasMany(RoomComboPricing::class, 'room_id');
    }

    /**
     * Relationship: Room has many special offers
     */
    public function specialOffers(): HasMany
    {
        return $this->hasMany(RoomSpecialOffer::class, 'room_id');
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }
    public function highlightAmenities()
    {
        return $this->hasMany(RoomHighlightAmenity::class, 'room_id');
    }

    public function views()
    {
        return $this->belongsToMany(View::class, 'room_views', 'room_id', 'view_id');
    }
    public function parkingRules()
    {
        return $this->hasMany(RoomParkingRule::class, 'room_id');
    }
    public function discountPricing()
    {
        return $this->hasMany(RoomDiscountPricing::class, 'room_id');
    }
    public function houseRule()
    {
        return $this->belongsToMany(HouseRule::class, 'room_house_rule', 'room_id', 'house_rule_id')->withPivot('value','house_rule_id');
    }
    public function roomPolicy()
    {
        return $this->hasMany(RoomPolicy::class, 'room_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function fixedCheckTime()
    {
        return $this->belongsToMany(FixedCheckTime::class, 'room_fixed_check_time')->withPivot('price','mon_buffer_price','tue_buffer_price','wed_buffer_price','thu_buffer_price','fri_buffer_price','sat_buffer_price','sun_buffer_price','sun_price','mon_price','tue_price','wed_price','thu_price','fri_price','sat_price');
    }
    public function fixedCheckTimeStd()
    {
        return $this->belongsToMany(FixedCheckTime::class, 'room_fixed_check_time')->where('code', FixedCheckTime::CODE_STD)->withPivot('price','mon_buffer_price','tue_buffer_price','wed_buffer_price','thu_buffer_price','fri_buffer_price','sat_buffer_price','sun_buffer_price','sun_price','mon_price','tue_price','wed_price','thu_price','fri_price','sat_price');
    }
    public function roomCheckinInstruction()
    {
        return $this->hasMany(RoomCheckinInstruction::class, 'room_id');
    }
    public function checkinMethods()
    {
        return $this->belongsToMany( CheckinMethod::class, 'room_checkin_instruction', 'room_id', 'checkin_method_id')->withPivot('way_to_house_message');
    }

    public function checkoutInstructionType(){
        return $this->belongsToMany(CheckoutInstructionType::class, 'room_checkout_instructions_type')->withPivot('content');
    }
    public function orders(){
        return $this->hasMany(Order::class, 'room_id');
    }
    public function medias()
    {
        return $this->hasMany(Media::class, 'room_id');
    }

    public function getReviewsAttribute()
    {
        if ($this->relationLoaded('reviews')) {
            return $this->getRelation('reviews');
        }

        if (!$this->relationLoaded('orders')) {
            $this->load('orders.review');
        }

        return $this->orders
            ->filter(fn($order) => $order->review !== null)
            ->map(fn($order) => $order->review)
            ->values();
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'room_id', 'user_id')->withPivot('is_favorite')->withTimestamps();
    }

    public function roomLock()
    {
        return $this->hasMany(RoomLock::class, 'room_id');
    }
    public function reviews()
    {
        return $this->hasManyThrough(
            Review::class,
            Order::class,
            'room_id',
            'order_id',
            'id',
            'id'
        );
    }
    public function roomCodeMapping()
    {
        return $this->hasOne(RoomCodeMapping::class, 'room_id');
    }

    public function icalFileConfigs()
    {
            return $this->hasMany(RoomIcalFileConfig::class, 'room_id');
    }
}
