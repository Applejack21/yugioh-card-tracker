<?php

namespace App\Jobs;

use App\Actions\Card\CreateCard;
use App\Actions\Card\UpdateCard;
use App\Models\Card;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

class ImportCardInfo implements ShouldQueue
{
    use Batchable;
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    public Collection $cards;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $cards)
    {
        $this->cards = $cards;
        $this->onQueue('high');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            // Determine if the batch has been cancelled...

            return;
        }

        if ($this->cards && $this->cards->count() > 0) {
            $this->cards->each(function ($card) {
                $dbCard = Card::where('card_id', $card['id'])->first();

                if (is_null($dbCard)) {
                    $card = (new CreateCard)->execute($card);
                } else {
                    $card = (new UpdateCard)->execute($dbCard, $card);
                }
            });
        }
    }
}
