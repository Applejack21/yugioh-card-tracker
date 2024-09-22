<?php

namespace App\Jobs;

use App\Http\Integrations\YgoProDeck\Requests\CardInfo;
use App\Http\Integrations\YgoProDeck\YgoProDeckConnector;
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
		$request = new CardInfo;

		$paginator = $connector->paginate($request);

		$batchData = [];

		foreach ($paginator as $response) {
			if ($response->successful()) {
				$data = $response->collect('data');

				array_push($batchData, new ImportCardInfo($data));
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
