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
        Schema::create('card_user', function (Blueprint $table) {
            $table->foreignId('card_id')->constrained('cards');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('card_rarity_id')->constrained('card_rarities');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_user');
    }
};
