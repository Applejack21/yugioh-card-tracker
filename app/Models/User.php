<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use Searchable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'public' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The cards this user has collected.
     */
    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class)
            ->withPivot('card_rarity_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * Is this user's account public?
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Scope a query to only include public users.
     */
    public function scopeIsPublic(Builder $query): void
    {
        $query->where('public', 1);
    }

    /**
     * Scope a query to only include non-public users.
     */
    public function scopeIsNotPublic(Builder $query): void
    {
        $query->where('public', 0);
    }

    /**
     * Concatenated the user's first and last names (if they've filled them in).
     */
    public function concatenatedName(): ?string
    {
        $name = null;

        if ($this->first_name) {
            $name = $this->first_name;
        }

        if ($this->last_name) {
            $name .= ' ' . $this->last_name;
        }

        return $name;
    }

    /**
     * Update the way we get the user's default profile photo if they don't have one uploaded.
     */
    public function defaultProfilePhotoUrl()
    {
        // Get their concatenated name.
        $name = $this->concatenatedName();

        // If they haven't added their name, use their username.
        $name = $name ?? $this->username;

        return "https://ui-avatars.com/api/?name=$name&color=FFFFFF&background=6AA9D5&format=svg";
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'username' => $this->username,
        ];
    }

    /**
     * Check to see if the user has this card in their collection already.
     *
     * @param  int  $cardId  The card ID to find.
     * @param  int  $rarityId  The card rarity to find.
     */
    public function hasCardInCollection(int $cardId, ?int $rarityId = null): ?Card
    {
        return $this->cards()
            ->where('card_user.card_id', $cardId)
            ->when($rarityId, function ($query, int $val) {
                $query->where('card_rarity_id', $val);
            })
            ->first();
    }
}
