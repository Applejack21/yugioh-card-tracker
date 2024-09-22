<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardRarity extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Cards that have this rarity.
     */
    public function cardCardSets(): HasMany
    {
        return $this->hasMany(CardCardSet::class);
    }
}
