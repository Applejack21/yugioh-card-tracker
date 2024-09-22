<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            // All cards have this info returned.
            $table->unsignedBigInteger('card_id'); // ID or Passcode of card.
            $table->string('name');
            $table->string('type');
            $table->string('human_readable_card_type');
            $table->string('frame_type');
            $table->longText('description');
            $table->string('ygo_pro_deck_url', 500); // Chances the URL is quite long.

            // Optional per card (based on card type).
            $table->integer('atk')->nullable();
            $table->integer('def')->nullable();
            $table->integer('level')->nullable();
            $table->string('race')->nullable();
            $table->string('attribute')->nullable();
            $table->string('archetype')->nullable();
            $table->string('scale')->nullable();
            $table->integer('link_val')->nullable();
            $table->json('type_line')->default(new Expression('(JSON_ARRAY())'))->nullable(); // This is the [<type>/<type>] above the description of the card.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
