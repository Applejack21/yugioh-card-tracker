<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('card_sets', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('code');
			$table->integer('total_cards')->default(0);
			$table->date('release_date')->nullable();
			$table->string('ygo_pro_deck_image_url')->nullable();
			$table->timestamps();

			// Codes can appear multiple times but names are unique per code.
			$table->index(['name', 'code'], 'index_name_and_code');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('card_sets');
	}
};
