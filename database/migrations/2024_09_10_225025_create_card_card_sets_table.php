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
        Schema::create('card_card_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards');
            $table->foreignId('card_set_id')->constrained('card_sets');
            $table->foreignId('card_rarity_id')->constrained('card_rarities');
            $table->string('code')->nullable(); // The code on the card for this set.
            $table->decimal('price', 12, 2)->default(0); // In $.
            $table->timestamps();

            $table->index(['card_id', 'card_set_id', 'code'], 'index_card_set_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_card_sets');
    }
};
