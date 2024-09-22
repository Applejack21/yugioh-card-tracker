<?php

namespace App\Actions\BanList;

use App\Models\BanList;

class CreateBanList
{
    /**
     * Import a new ban list to the database.
     *
     * @param  array  $request  Request param data.
     */
    public function execute(array $request): BanList
    {
        $banList = BanList::create([
            ...$request,
        ]);

        return tap($banList)->refresh();
    }
}
