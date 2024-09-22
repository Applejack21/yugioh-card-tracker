<?php

namespace App\Actions\CardPrice;

use App\Models\CardPrice;

class UpdateCardPrice
{
    /**
     * Update the card price in the database.
     *
     * @param  CardPrice  $cardPrice  The card price to update.
     * @param  array  $request  Request param data.
     */
    public function execute(CardPrice $cardPrice, array $request): CardPrice
    {
        $cardPrice->update([
            ...$request,
        ]);

        return tap($cardPrice)->refresh();
    }
}
