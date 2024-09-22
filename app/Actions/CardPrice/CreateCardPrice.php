<?php

namespace App\Actions\CardPrice;

use App\Models\CardPrice;

class CreateCardPrice
{
    /**
     * Import a new card price to the database.
     *
     * @param  array  $request  Request param data.
     */
    public function execute(array $request): CardPrice
    {
        $cardPrice = CardPrice::create([
            ...$request,
        ]);

        return tap($cardPrice)->refresh();
    }
}
