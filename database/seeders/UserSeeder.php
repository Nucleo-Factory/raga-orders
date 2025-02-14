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
        // Note: Basic users are already created in CompanySeeder
        // Here we can create additional users if needed

        // Create some verified users
        User::factory()
            ->count(5)
            ->create();

        // Create some unverified users
        User::factory()
            ->unverified()
            ->count(3)
            ->create();

        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
