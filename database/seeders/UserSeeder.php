<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if it doesn't exist.
        if (is_null(User::where('email', 'test@example.com')->first())) {
            User::factory()->create([
                'username' => 'test-user',
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
            ]);
        }

        // Create some more users.
        User::factory(10)->create();
    }
}
