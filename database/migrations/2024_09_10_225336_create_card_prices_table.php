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
        Schema::create('card_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained();
            $table->decimal('cardmarket_price', 12, 2)->default(0);
            $table->decimal('tcgplayer_price', 12, 2)->default(0);
            $table->decimal('ebay_price', 12, 2)->default(0);
            $table->decimal('amazon_price', 12, 2)->default(0);
            $table->decimal('coolstuffinc_price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_prices');
    }
};
