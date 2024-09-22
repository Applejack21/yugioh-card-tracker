<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardCardSet extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The card this is linked to.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * The card set this is linked to.
     */
    public function cardSet(): BelongsTo
    {
        return $this->belongsTo(CardSet::class);
    }

    /**
     * The rarity of this card in this set.
     */
    public function cardRarity(): BelongsTo
    {
        return $this->belongsTo(CardRarity::class);
    }
}
