<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 companies with 3 users each
        Company::factory()
            ->count(5)
            ->has(User::factory()->count(3))
            ->create();

        // Create a main company for testing with specific data
        $mainCompany = Company::factory()->create([
            'name' => 'Main Test Company',
            'address' => '123 Main Street, Test City',
        ]);

        // Create an admin user for the main company
        User::factory()->create([
            'company_id' => $mainCompany->id,
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
