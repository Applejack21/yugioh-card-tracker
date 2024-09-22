<?php

namespace App\Actions\CardCardSet;

use App\Models\CardCardSet;

class CreateCardCardSet
{
    /**
     * Import a new card card set to the database.
     *
     * @param  array  $request  Request param data.
     */
    public function execute(array $request): CardCardSet
    {
        $cardCardSet = CardCardSet::create([
            ...$request,
        ]);

        return tap($cardCardSet)->refresh();
    }
}
