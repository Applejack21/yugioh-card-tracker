<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BanList extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The card that this ban list is for.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
