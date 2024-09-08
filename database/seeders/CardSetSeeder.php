<?php

namespace Database\Seeders;

use App\Models\CardSet;
use Illuminate\Database\Seeder;

class CardSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardSet::factory()->count(10)->create();
    }
}
