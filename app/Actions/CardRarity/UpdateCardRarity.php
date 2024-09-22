<?php

namespace App\Actions\CardRarity;

use App\Models\CardRarity;

class UpdateCardRarity
{
    /**
     * Update the card rarity in the database.
     *
     * @param  CardRarity  $cardRarity  The card rarity to update.
     * @param  array  $request  Request param data.
     */
    public function execute(CardRarity $cardRarity, array $request): CardRarity
    {
        $cardRarity->update([
            ...$request,
        ]);

        return tap($cardRarity)->refresh();
    }
}
