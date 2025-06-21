<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserContact extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'value',
    ];

    /**
     * The attributes that should be cast to native types or enums.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => ContactTypeEnum::class,
    ];

    /**
     * Get the user that owns this contact.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include phone-type user contacts.
     *
     * Filters contacts with types: mobile, home, or work.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePhones($query)
    {
        return $query->whereIn('type', [
            ContactTypeEnum::MOBILE->value,
            ContactTypeEnum::HOME->value,
            ContactTypeEnum::WORK->value,
        ]);
    }

    /**
     * Scope a query to only include social network contacts.
     *
     * Filters contacts with types: WhatsApp, Facebook, or Instagram.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSocialNetworks($query)
    {
        return $query->whereIn('type', [
            ContactTypeEnum::WHATSAPP->value,
            ContactTypeEnum::FACEBOOK->value,
            ContactTypeEnum::INSTAGRAM->value,
        ]);
    }
}
