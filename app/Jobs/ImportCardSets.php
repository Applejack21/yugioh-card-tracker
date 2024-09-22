<?php

namespace App\Jobs;

use App\Actions\CardSet\CreateCardSet;
use App\Actions\CardSet\UpdateCardSet;
use App\Http\Integrations\YgoProDeck\Requests\CardSets;
use App\Http\Integrations\YgoProDeck\YgoProDeckConnector;
use App\Models\CardSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportCardSets implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('high');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $connector = new YgoProDeckConnector;
        $request = new CardSets;

        $response = $connector->send($request);

        if ($response->successful()) {
            $cardSets = $response->collect();

            $cardSets->each(function ($cardSet) {
                $dbCardSet = CardSet::where('name', $cardSet['set_name'])
                    ->where('code', $cardSet['set_code'])
                    ->first();

                if (is_null($dbCardSet)) {
                    $cardSet = (new CreateCardSet)->execute($cardSet);
                } else {
                    $cardSet = (new UpdateCardSet)->execute($dbCardSet, $cardSet);
                }
            });
        }
    }
}
