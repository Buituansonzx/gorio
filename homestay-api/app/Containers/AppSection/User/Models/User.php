<?php

namespace App\Containers\AppSection\User\Models;

use App\Containers\AppSection\Authorization\Enums\Role as RoleEnum;
use App\Containers\AppSection\User\Data\Collections\UserCollection;
use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Models\UserModel as ParentUserModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Token;
use Spatie\Permission\Traits\HasRoles;

final class User extends ParentUserModel
{
    protected $table = 'users';

    use HasRoles, HasUuids, SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    // Using auto-increment ID (removed HasUuids trait)

    const STATUS_ACTIVE = 1;

    //Khi đăng ký mà không đăng nhập ngay thì để trạng thái inactive (default trong db)
    const STATUS_INACTIVE = -1;

    const CODE_SEND_OTP = 'otp_send';

    const CODE_SEND_SMS_FAILED = 'sms_failed';
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'immutable_datetime',
        'password' => 'hashed',
        'gender' => Gender::class,
        'birth' => 'immutable_date',
        'otp_expires_at' => 'datetime',
        'locked_until' => 'datetime',
        'status' => 'integer',
        'data' => 'array',
    ];

    public function newCollection(array $models = []): UserCollection
    {
        return new UserCollection($models);
    }

    public function scopeNotSeeded($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('data->is_seeded')
              ->orWhere('data->is_seeded', false);
        });
    }

    /**
     * Allows Passport to find the user by phone or email (case-insensitive).
     */
    public function findForPassport(string $username): self|null
    {
        // Tìm theo phone trước (cho login OTP), sau đó tìm theo email
        return self::where('phone', $username)
            ->orWhereRaw('lower(email) = lower(?)', [$username])
            ->first();
    }

    public function isSuperAdmin(): bool
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (!$this->hasRole(RoleEnum::SUPER_ADMIN, $guard)) {
                return false;
            }
        }

        return true;
    }

    protected function email(): Attribute
    {
        return new Attribute(
            get: static fn (string|null $value): string|null => is_null($value) ? null : strtolower($value),
        );
    }

    /**
     * Validate user credentials for Passport Password Grant.
     * Validates OTP instead of traditional password.
     */
    public function validateForPassportPasswordGrant(string $password): bool
    {
        // TODO: Remove auto-pass OTP when SMS OTP service is implemented
        // Allow auto-pass OTP (000000) for testing
        if ($password === '000000') {
            return true;
        }

        // Validate OTP: check if OTP matches and hasn't expired
        return $this->otp_code === $password
            && $this->otp_expires_at
            && now()->lessThanOrEqualTo($this->otp_expires_at);
    }
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favoritedRooms()
    {
        return $this->belongsToMany(Room::class, 'favorites') ->withPivot('is_favorite')
            ->wherePivot('is_favorite', true)
            ->withTimestamps();
    }
    public function favoriteRooms()
    {
        return $this->belongsToMany(Room::class, 'favorites') ->withPivot('is_favorite')
            ->withTimestamps();
    }
    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
