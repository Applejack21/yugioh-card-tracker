<?php

use App\Jobs\BatchImportCards;
use App\Jobs\ImportCardSets;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Import card sets/cards. Card batch is at a specific time to let card sets be imported/updated.
Schedule::job(new ImportCardSets)->daily();
Schedule::job(new BatchImportCards)->dailyAt('02:00');
