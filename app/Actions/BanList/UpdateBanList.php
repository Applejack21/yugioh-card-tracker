<?php

namespace App\Actions\BanList;

use App\Models\BanList;

class UpdateBanList
{
    /**
     * Update the ban list in the database.
     *
     * @param  BanList  $banList  The ban list to update.
     * @param  array  $request  Request param data.
     */
    public function execute(BanList $banList, array $request): BanList
    {
        $banList->update([
            ...$request,
        ]);

        return tap($banList)->refresh();
    }
}
