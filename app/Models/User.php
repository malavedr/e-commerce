<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use App\Enums\UserRoleEnum;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'billing_name',
        'billing_tax_id',
        'billing_address_line',
        'billing_province',
        'billing_locality',
        'billing_zipcode',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatusEnum::class,
    ];

    /**
     * Get all the contact methods associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(UserContact::class);
    }

    /**
     * Get all the delivery addresses registered by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryAddresses(): HasMany
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    /**
     * Get all orders placed by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', UserStatusEnum::ACTIVE->value);
    }

    /**
     * Scope a query to only include users who haven't verified their email.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnverified($query)
    {
        return $query->where('status', UserStatusEnum::UNVERIFIED->value);
    }

    /**
     * Scope a query to only include users who are not active.
     *
     * Includes statuses: inactive, suspended, and banned.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnactive($query)
    {
        return $query->whereIn('status', [
            UserStatusEnum::INACTIVE->value,
            UserStatusEnum::SUSPENDED->value,
            UserStatusEnum::BANNED->value,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === UserStatusEnum::ACTIVE;
    }

    public function isOwner($user_id): bool
    {
        return $this->id === $user_id;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN->value;
    }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function activeDeliveryAddress()
    {
        return $this->deliveryAddresses()->active()->first();
    }

}
