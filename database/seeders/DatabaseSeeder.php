<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,     // Companies must be created first
            UserSeeder::class,        // Users depend on companies
            ProductSeeder::class,     // Products are independent
            PurchaseOrderSeeder::class, // Purchase orders depend on companies and products
        ]);
    }
}
