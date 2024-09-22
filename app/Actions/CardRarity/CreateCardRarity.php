<?php

namespace App\Actions\CardRarity;

use App\Models\CardRarity;

class CreateCardRarity
{
    /**
     * Import a new card rarity to the database.
     *
     * @param  array  $request  Request param data.
     */
    public function execute(array $request): CardRarity
    {
        $cardRarity = CardRarity::create([
            ...$request,
        ]);

        return tap($cardRarity)->refresh();
    }
}
