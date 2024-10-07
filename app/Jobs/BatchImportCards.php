<?php

namespace App\Jobs;

use App\Http\Integrations\YgoProDeck\Requests\CardInfo;
use App\Http\Integrations\YgoProDeck\Requests\CheckDbVer;
use App\Http\Integrations\YgoProDeck\YgoProDeckConnector;
use App\Models\YgoVersion;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Throwable;

class BatchImportCards implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes.

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $connector = new YgoProDeckConnector;
        $dbCheck = new CheckDbVer;
        $request = new CardInfo;
        $updateImages = false;

        // Get the current version of YGO database in our database.
        $current = YgoVersion::first();

        $response = $connector->send($dbCheck);
        $ygoData = $response->collect()[0];

        // If no current data in table then add it and update images.
        if (is_null($current)) {
            $current = YgoVersion::create([
                ...$ygoData,
            ]);

            $updateImages = true;
        }

        // Check the current database version with the API version and if its older update it.
        if ($ygoData['database_version'] > $current->database_version) {
            $current->update([
                ...$ygoData,
            ]);
            $updateImages = true;
        }

        $paginator = $connector->paginate($request);

        $batchData = [];

        foreach ($paginator as $response) {
            if ($response->successful()) {
                $data = $response->collect('data');

                array_push($batchData, new ImportCardInfo($data, $updateImages));
            }
        }

        $batch = Bus::batch($batchData)->before(function (Batch $batch) {
            // The batch has been created but no jobs have been added...
        })->progress(function (Batch $batch) {
            // A single job has completed successfully...
        })->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })
            ->name('Import Cards')
            ->dispatch();
    }
}
