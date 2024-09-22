<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
}
