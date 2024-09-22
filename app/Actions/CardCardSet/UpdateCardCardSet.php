<?php

namespace App\Actions\CardCardSet;

use App\Models\CardCardSet;

class UpdateCardCardSet
{
    /**
     * Update the card price in the database.
     *
     * @param  CardCardSet  $cardCardSet  The card card set to update.
     * @param  array  $request  Request param data.
     */
    public function execute(CardCardSet $cardCardSet, array $request): CardCardSet
    {
        $cardCardSet->update([
            ...$request,
        ]);

        return tap($cardCardSet)->refresh();
    }
}
