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
                KanbanBoardSeeder::class, // Usar KanbanBoardSeeder en lugar de KanbanSeeder
                HubSeeder::class,
                NotificationTypeSeeder::class, // Added notification types seeder
            ]);
        } finally {
            // Asegurarse de que las restricciones de clave foránea se reactiven
            Schema::enableForeignKeyConstraints();
        }
    }
}
