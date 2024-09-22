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
		Schema::table('cards', function (Blueprint $table) {
			$table->date('tcg_date')->after('type_line')->nullable();
			$table->date('ocg_date')->after('tcg_date')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('cards', function (Blueprint $table) {
			$table->dropColumn('tcg_date', 'ocg_date');
		});
	}
};
