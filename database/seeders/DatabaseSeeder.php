<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Desactivar temporalmente las restricciones de clave foránea para toda la operación de seeding
        Schema::disableForeignKeyConstraints();

        try {
            $this->call([
                CompanySeeder::class,     // Companies must be created first
                UserSeeder::class,        // Users depend on companies
                ProductSeeder::class,     // Products are independent
                PurchaseOrderSeeder::class, // Purchase orders depend on companies and products
                KanbanBoardSeeder::class, // Usar KanbanBoardSeeder en lugar de KanbanSeeder
            ]);
        } finally {
            // Asegurarse de que las restricciones de clave foránea se reactiven
            Schema::enableForeignKeyConstraints();
        }
    }
}
