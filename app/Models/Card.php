<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Card extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Searchable;

    protected $guarded = [];

    protected $casts = [
        'type_line' => 'array',
        'tcg_date' => 'date',
        'ocg_date' => 'date',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }

    /**
     * The card sets this card has.
     */
    public function cardSets(): HasMany
    {
        return $this->hasMany(CardCardSet::class);
    }

    /**
     * The ban list this card has.
     */
    public function banList(): HasOne
    {
        return $this->hasOne(BanList::class);
    }

    /**
     * The prices this card has.
     */
    public function cardPrice(): HasOne
    {
        return $this->hasOne(CardPrice::class);
    }

    /**
     * The users who have collected this card.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('card_rarity_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * Card user table also has card_rarity_id so link those.
     */
    public function rarities(): BelongsToMany
    {
        return $this->belongsToMany(CardRarity::class, 'card_user')
            ->withPivot('quantity', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    /**
     * Get total quantity the passed user has collected of this card. Regardless of rarities.
     *
     * @param  User|null  The user to check cards for. If null, will default to logged in user.
     */
    public function getTotalQuantity(?User $user = null): int
    {
        $user = $user ?: auth()->user();

        if ($user) {
            return $this->users()
                ->where('user_id', $user->id)
                ->where('user_id', 1)
                ->get()
                ->sum('pivot.quantity');
        }

        return 0;
    }

    /**
     * Get a list of quantites this user has collected per card rarity.
     *
     * @param  User|null  The user to check cards for. If null, will default to logged in user.
     */
    public function getQuantitiesPerRarity(?User $user = null): array
    {
        $user = $user ?: auth()->user();

        if ($user) {
            return $this->rarities()
                ->wherePivot('user_id', $user->id)
                ->get()
                ->map(function ($rarity) {
                    return [
                        'rarity_name' => $rarity->name,
                        'quantity' => $rarity->pivot->quantity,
                    ];
                })
                ->toArray();
        }

        return [];
    }

    /**
     * Get a list of card rarities names this card has.
     */
    public function getCardRarities(): Collection
    {
        // Get a list of rarities IDs this card has.
        $sets = $this->cardsets()->pluck('card_rarity_id');

        // Return rarity records.
        return CardRarity::whereIn('id', $sets->toArray())
            ->get();
    }

    // /**
    //  * Decide if we should update the image of the card.
    //  * Deprecated way of checking for image update.
    //  *
    //  * @param  string  $imageUrl  The image URL to get check against.
    //  */
    // public function shouldUpdateImage(string $imageUrl): bool
    // {
    // 	// Get the image.
    // 	$image = $this->getFirstMedia('image');

    // 	// Doesn't have an image so add it. No need to compare hashes.
    // 	if (is_null($image)) {
    // 		$this->addMediaFromUrl($imageUrl)
    // 			->toMediaCollection('image');
    // 		return;
    // 	}

    // 	// Get the image from URL.
    // 	$response = Http::get($imageUrl);

    // 	if ($response->successful()) {
    // 		$content = $response->body();

    // 		// Get hashes.
    // 		$hash = md5($content);
    // 		$currentHash = md5_file($image->getPath());

    // 		// Compared hashes and add image if hashes are different.
    // 		if ($hash !== $currentHash) {
    // 			$this->addMediaFromUrl($imageUrl)
    // 				->toMediaCollection('image');
    // 		}
    // 	}

    // 	return false;
    // }
}
